<?php

declare(strict_types=1);

namespace VoiceML;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use VoiceML\Exception\ApiException;
use VoiceML\Exception\AuthenticationException;
use VoiceML\Exception\BadRequestException;
use VoiceML\Exception\ConflictException;
use VoiceML\Exception\GoneException;
use VoiceML\Exception\NotFoundException;
use VoiceML\Exception\NotImplementedApiException;
use VoiceML\Exception\PermissionDeniedException;
use VoiceML\Exception\RateLimitException;
use VoiceML\Exception\ServerException;

/**
 * Guzzle-backed HTTP transport.
 *
 * - Auth: HTTP Basic with `accountSid` as username and per-tenant `apiKey` as password.
 *   The same pair the Twilio PHP SDK takes in its constructor — drop-in compatible.
 * - Wire format: requests are form-urlencoded by default (Twilio convention). Pass `json: true`
 *   via {@see RequestOptions} if the server has been told to accept JSON. Responses are JSON.
 * - Retries: 429 + 5xx are retried up to `maxRetries` times with exponential backoff, honoring
 *   the `Retry-After` header when the server emits one.
 * - Binary fetch: {@see fetchBytes()} follows the 302→S3 redirect that `GET /Recordings/{sid}.wav`
 *   issues when audio has been archived. Callers usually only care about the final bytes.
 */
final class Transport
{
    private const RETRYABLE_STATUSES = [429, 500, 502, 503, 504];

    private readonly ClientInterface $http;

    public function __construct(private readonly ClientOptions $options)
    {
        $this->http = $options->httpClient ?? new GuzzleClient([
            'base_uri' => $options->baseUrl . '/',
            'timeout' => $options->timeout,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);
    }

    public function accountSid(): string
    {
        return $this->options->accountSid;
    }

    public function baseUrl(): string
    {
        return $this->options->baseUrl;
    }

    /**
     * Issue a request and return the parsed JSON body (or null for 204).
     *
     * @param string                    $method One of GET / POST / PUT / DELETE / PATCH.
     * @param string                    $path   Server-relative path (begins with `/`).
     * @param array<string,mixed>|null  $query  Query params. Null/undefined values are dropped.
     * @param array<string,mixed>|null  $form   Form-encoded body. Bool→"true"/"false".
     * @param mixed                     $json   When non-null and `$form` is null, sent as JSON.
     *
     * @return mixed Decoded JSON body, or null on empty (204) responses.
     */
    public function request(
        string $method,
        string $path,
        ?array $query = null,
        ?array $form = null,
        mixed $json = null,
    ): mixed {
        $options = $this->buildRequestOptions($query, $form, $json);

        $lastException = null;
        for ($attempt = 0; $attempt <= $this->options->maxRetries; $attempt++) {
            try {
                $response = $this->http->request($method, $this->absUrl($path), $options);
            } catch (ConnectException $e) {
                $lastException = $e;
                if ($attempt >= $this->options->maxRetries) {
                    throw new ApiException(
                        sprintf('transport error after %d attempts: %s', $attempt + 1, $e->getMessage()),
                        statusCode: 0,
                        previous: $e,
                    );
                }
                $this->sleepBackoff($attempt, null);
                continue;
            } catch (RequestException $e) {
                $response = $e->getResponse();
                if ($response === null) {
                    $lastException = $e;
                    if ($attempt >= $this->options->maxRetries) {
                        throw new ApiException(
                            sprintf('transport error after %d attempts: %s', $attempt + 1, $e->getMessage()),
                            statusCode: 0,
                            previous: $e,
                        );
                    }
                    $this->sleepBackoff($attempt, null);
                    continue;
                }
            } catch (GuzzleException $e) {
                $lastException = $e;
                if ($attempt >= $this->options->maxRetries) {
                    throw new ApiException(
                        sprintf('transport error after %d attempts: %s', $attempt + 1, $e->getMessage()),
                        statusCode: 0,
                        previous: $e,
                    );
                }
                $this->sleepBackoff($attempt, null);
                continue;
            }

            $status = $response->getStatusCode();
            if (in_array($status, self::RETRYABLE_STATUSES, true) && $attempt < $this->options->maxRetries) {
                $this->sleepBackoff($attempt, $response);
                continue;
            }

            return $this->parseResponse($response);
        }

        // Unreachable in normal flow — the loop body either returns or rethrows.
        // @codeCoverageIgnoreStart
        if ($lastException !== null) {
            throw new ApiException(
                'retries exhausted: ' . $lastException->getMessage(),
                statusCode: 0,
                previous: $lastException,
            );
        }
        throw new ApiException('unreachable retry exhaustion', statusCode: 0);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Binary fetch for recording audio. Follows the single 302→S3 redirect.
     *
     * @return array{status:int, body:string, headers:array<string,array<int,string>>}
     */
    public function fetchBytes(string $path): array
    {
        try {
            $response = $this->http->request('GET', $this->absUrl($path), [
                RequestOptions::AUTH => [$this->options->accountSid, $this->options->apiKey],
                RequestOptions::HEADERS => $this->headers(),
                RequestOptions::ALLOW_REDIRECTS => true,
                RequestOptions::HTTP_ERRORS => false,
            ]);
        } catch (GuzzleException $e) {
            throw new ApiException(
                'transport error fetching bytes: ' . $e->getMessage(),
                statusCode: 0,
                previous: $e,
            );
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            $this->parseResponse($response); // throws
        }

        return [
            'status' => $status,
            'body' => (string) $response->getBody(),
            'headers' => $response->getHeaders(),
        ];
    }

    /**
     * @param array<string,mixed>|null $query
     * @param array<string,mixed>|null $form
     *
     * @return array<string,mixed>
     */
    private function buildRequestOptions(?array $query, ?array $form, mixed $json): array
    {
        $options = [
            RequestOptions::AUTH => [$this->options->accountSid, $this->options->apiKey],
            RequestOptions::HEADERS => $this->headers(),
            RequestOptions::HTTP_ERRORS => false,
        ];

        if ($query !== null) {
            $cleaned = self::cleanQuery($query);
            if ($cleaned !== []) {
                $options[RequestOptions::QUERY] = $cleaned;
            }
        }

        if ($form !== null) {
            $cleaned = self::cleanForm($form);
            if ($cleaned !== []) {
                $options[RequestOptions::FORM_PARAMS] = $cleaned;
            }
        } elseif ($json !== null) {
            $options[RequestOptions::JSON] = $json;
        }

        return $options;
    }

    /**
     * @return array<string,string>
     */
    private function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'User-Agent' => $this->options->userAgent,
        ];
    }

    private function absUrl(string $path): string
    {
        // Guzzle treats absolute URIs as-is; relative URIs resolve against base_uri.
        return $this->options->baseUrl . $path;
    }

    private function parseResponse(ResponseInterface $response): mixed
    {
        $status = $response->getStatusCode();
        $bodyText = (string) $response->getBody();

        if ($status >= 200 && $status < 300) {
            if ($bodyText === '') {
                return null;
            }
            try {
                return json_decode($bodyText, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                throw new ApiException(
                    'non-JSON success response: ' . substr($bodyText, 0, 200),
                    statusCode: $status,
                    body: $bodyText,
                    previous: $e,
                );
            }
        }

        $body = $bodyText;
        try {
            $decoded = json_decode($bodyText, true, 512, JSON_THROW_ON_ERROR);
            $body = $decoded;
        } catch (\JsonException) {
            // Keep raw text body.
        }

        $code = null;
        $message = sprintf('HTTP %d', $status);
        if (is_array($body)) {
            if (isset($body['code']) && (is_int($body['code']) || is_string($body['code']))) {
                $code = $body['code'];
            }
            if (isset($body['message']) && is_string($body['message']) && $body['message'] !== '') {
                $message = $body['message'];
            }
        }

        throw self::makeApiException($status, $code, $body, $message);
    }

    private static function makeApiException(int $status, int|string|null $code, mixed $body, string $message): ApiException
    {
        return match (true) {
            $status === 400 => new BadRequestException($message, $status, $code, $body),
            $status === 401 => new AuthenticationException($message, $status, $code, $body),
            $status === 403 => new PermissionDeniedException($message, $status, $code, $body),
            $status === 404 => new NotFoundException($message, $status, $code, $body),
            $status === 409 => new ConflictException($message, $status, $code, $body),
            $status === 410 => new GoneException($message, $status, $code, $body),
            $status === 429 => new RateLimitException($message, $status, $code, $body),
            $status === 501 => new NotImplementedApiException($message, $status, $code, $body),
            $status >= 500 && $status < 600 => new ServerException($message, $status, $code, $body),
            default => new ApiException($message, $status, $code, $body),
        };
    }

    /**
     * @param array<string,mixed> $params
     *
     * @return array<string,mixed>
     */
    private static function cleanQuery(array $params): array
    {
        $out = [];
        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_bool($value)) {
                $out[$key] = $value ? 'true' : 'false';
            } else {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    /**
     * @param array<string,mixed> $form
     *
     * @return array<string,mixed>
     */
    private static function cleanForm(array $form): array
    {
        $out = [];
        foreach ($form as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_bool($value)) {
                $out[$key] = $value ? 'true' : 'false';
            } elseif (is_array($value)) {
                $out[$key] = array_map(static fn ($v) => is_bool($v) ? ($v ? 'true' : 'false') : $v, $value);
            } else {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    private function sleepBackoff(int $attempt, ?ResponseInterface $response): void
    {
        $delay = self::backoffSeconds($attempt, $response);
        if ($delay > 0) {
            usleep((int) round($delay * 1_000_000));
        }
    }

    private static function backoffSeconds(int $attempt, ?ResponseInterface $response): float
    {
        if ($response !== null && $response->hasHeader('Retry-After')) {
            $retryAfter = $response->getHeaderLine('Retry-After');
            if (is_numeric($retryAfter)) {
                return max(0.0, (float) $retryAfter);
            }
        }
        return (float) min(8.0, 0.5 * (2 ** $attempt));
    }
}
