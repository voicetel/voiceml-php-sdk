<?php

declare(strict_types=1);

namespace VoiceML;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\NetworkTimeoutException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Client\NetworkExceptionInterface;
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
 * - Wire format: requests are form-urlencoded. List-valued parameters are emitted as
 *   repeated keys (`Key=a&Key=b`), matching how the server reads form bodies. The body
 *   is built by this class and sent via {@see RequestOptions::BODY} with an explicit
 *   Content-Type header, never through `form_params` (whose `http_build_query()` encodes
 *   arrays as `Key[0]=…`, which the server ignores).
 * - Retries: GET/HEAD/PUT/DELETE retry on 429/500/502/503/504 and transport errors.
 *   POST/PATCH retry only on 429 and on connection-establishment failures where the
 *   request provably was not sent (DNS failure, connection refused, connect timeout,
 *   TLS handshake failure) — never on 5xx, read timeouts or connection resets, so a
     *   timed-out `POST /Calls` is not automatically replayed. `Retry-After` is honoured as
 *   delta-seconds or HTTP-date; when it exceeds 60s the error is raised immediately
 *   with the parsed value exposed on it.
 * - Every request carries the configured `timeout` and disables Guzzle-level redirect
 *   following ({@see RequestOptions::ALLOW_REDIRECTS} false) so redirects are handled
 *   by {@see fetchBytes()} with the documented safety rules.
 * - Binary fetch: {@see fetchBytes()} follows the 302→S3 redirect manually, stripping
 *   the Authorization header whenever the host or scheme changes and refusing an
 *   https→http downgrade.
 */
final class Transport
{
    private const RETRYABLE_STATUSES = [429, 500, 502, 503, 504];
    private const IDEMPOTENT_METHODS = ['GET', 'HEAD', 'DELETE', 'PUT'];
    private const MAX_RETRY_AFTER_SECONDS = 60.0;
    private const BACKOFF_BASE_SECONDS = 0.5;
    private const BACKOFF_MAX_SECONDS = 8.0;
    private const MAX_REDIRECTS = 3;

    private readonly ClientInterface $http;
    private readonly string $baseUrl;

    /**
     * @param string|null           $baseUrl Effective base URL for this transport. Defaults to
     *                                       `$options->baseUrl`; product-scoped transports pass a
     *                                       subdomain (`conversations.voicetel.com`, …).
     * @param ClientInterface|null  $http    Shared underlying HTTP client. When null a fresh Guzzle
     *                                       client is created. {@see forBaseUrl()} reuses the existing
     *                                       one so connection pooling and injected mocks are shared.
     */
    public function __construct(
        private readonly ClientOptions $options,
        ?string $baseUrl = null,
        ?ClientInterface $http = null,
    ) {
        $this->baseUrl = rtrim($baseUrl ?? $options->baseUrl, '/');
        $this->http = $http ?? $options->httpClient ?? new GuzzleClient([
            'base_uri' => $this->baseUrl . '/',
            'timeout' => $options->timeout,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);
    }

    /**
     * Standalone transport for the credential-free diagnostic endpoints
     * (`/health`, `/openapi.*`). Never sends an Authorization header; uses
     * the caller's HTTP client and timeout when provided.
     */
    public static function forDiagnostics(
        ?string $baseUrl = null,
        ?ClientInterface $http = null,
        ?float $timeout = null,
    ): self {
        $options = new ClientOptions(
            accountSid: 'diagnostics',
            apiKey: 'diagnostics',
            baseUrl: $baseUrl,
            timeout: $timeout,
        );

        return new self($options, null, $http);
    }

    /**
     * Return a transport pinned to a different product base URL, reusing this
     * transport's HTTP client (and thus its connection pool / injected mock).
     */
    public function forBaseUrl(string $baseUrl): self
    {
        return new self($this->options, $baseUrl, $this->http);
    }

    public function accountSid(): string
    {
        return $this->options->accountSid;
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Redact credentials from var_dump()/print_r() output.
     *
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'baseUrl' => $this->baseUrl,
            'options' => $this->options,
            'http' => get_class($this->http),
        ];
    }

    /** @return array<string,mixed> */
    public function __serialize(): array
    {
        return ['baseUrl' => $this->baseUrl, 'apiKey' => '***'];
    }

    /**
     * Issue a request and return the parsed JSON body (or null for 204).
     *
     * @param string                    $method One of GET / POST / PUT / DELETE / PATCH.
     * @param string                    $path   Server-relative path (begins with `/`).
     * @param array<string,mixed>|null  $query  Query params. Null/undefined values are dropped;
     *                                          list values are emitted as repeated keys.
     * @param array<string,mixed>|null  $form   Form-encoded body. Bool→"true"/"false"; list values
     *                                          are emitted as repeated keys.
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
        RequestRules::validate($method, $path, $query, $form);
        $response = $this->send(
            $method,
            $this->absUrl($path),
            $this->buildRequestOptions($query, $form, $json),
        );

        return $this->parseResponse($response);
    }

    /**
     * Issue a request without HTTP Basic auth. Used by the diagnostic endpoints
     * (`/health`, `/openapi.*` carry `security: []` in the spec). Everything else
     * (timeout, User-Agent, retry policy, injected HTTP client) applies as usual.
     *
     * @param string                   $method HTTP method.
     * @param string                   $path   Server-relative path (begins with `/`).
     * @param array<string,mixed>|null $query  Query params.
     *
     * @return mixed Decoded JSON body, or null on empty (204) responses.
     */
    public function requestUnauthenticated(
        string $method,
        string $path,
        ?array $query = null,
    ): mixed {
        $response = $this->send(
            $method,
            $this->absUrl($path),
            $this->buildRequestOptions($query, null, null, authenticated: false),
        );

        return $this->parseResponse($response);
    }

    /**
     * GET an absolute or server-relative page URI (typically a list envelope's
     * `next_page_uri`, followed verbatim). Relative URIs resolve against this
     * transport's scheme and host. An absolute URI whose scheme or host differs
     * from the configured host raises an {@see ApiException} — credentials are
     * never sent to a different origin.
     *
     * @return mixed Decoded JSON body.
     */
    public function requestPageUri(string $uri): mixed
    {
        return $this->request('GET', $this->resolvePageUri($uri));
    }

    /**
     * Binary fetch for recording audio and file downloads. Follows redirects
     * manually: the Authorization header is stripped as soon as the host or
     * scheme changes, and an https→http downgrade is refused with an
     * {@see ApiException}.
     *
     * @param string $path   Server-relative path (begins with `/`).
     * @param string $accept Value for the Accept header.
     *
     * @return array{status:int, body:string, headers:array<string,array<int,string>>}
     */
    public function fetchBytes(string $path, string $accept = 'audio/*, */*', bool $authenticated = true): array
    {
        $url = $this->absUrl($path);
        $useAuth = $authenticated;

        for ($hop = 0; $hop <= self::MAX_REDIRECTS; $hop++) {
            $response = $this->send('GET', $url, $this->buildBytesOptions($useAuth, $accept));

            $status = $response->getStatusCode();
            if ($status >= 300 && $status < 400 && $response->hasHeader('Location')) {
                $target = $this->resolveRedirect($url, $response->getHeaderLine('Location'));
                $response->getBody()->close();
                if ($this->isSchemeDowngrade($url, $target)) {
                    throw new ApiException(
                        'refusing to follow redirect that downgrades https to http: ' . $target,
                        statusCode: 0,
                    );
                }
                $useAuth = $useAuth && $this->isSameOrigin($url, $target);
                $url = $target;
                continue;
            }

            if ($status < 200 || $status >= 300) {
                $this->parseResponse($response); // throws
            }

            return [
                'status' => $status,
                'body' => (string) $response->getBody(),
                'headers' => $response->getHeaders(),
            ];
        }

        throw new ApiException(
            sprintf('more than %d redirects while fetching bytes', self::MAX_REDIRECTS),
            statusCode: 0,
        );
    }

    /**
     * @param array<string,mixed>|null $query
     * @param array<string,mixed>|null $form
     *
     * @return array<string,mixed>
     */
    private function buildRequestOptions(
        ?array $query,
        ?array $form,
        mixed $json,
        bool $authenticated = true,
    ): array {
        $options = [
            RequestOptions::AUTH => null,
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::TIMEOUT => $this->options->timeout,
            RequestOptions::ALLOW_REDIRECTS => false,
            RequestOptions::HEADERS => $this->headers(),
        ];
        if ($authenticated) {
            $options[RequestOptions::AUTH] = [$this->options->accountSid, $this->options->apiKey];
        }

        if ($query !== null) {
            $queryString = self::buildQueryString($query);
            if ($queryString !== '') {
                $options[RequestOptions::QUERY] = $queryString;
            }
        }

        if ($form !== null) {
            $bodyString = self::buildFormBody($form);
            if ($bodyString !== '') {
                $options[RequestOptions::BODY] = $bodyString;
                $options[RequestOptions::HEADERS]['Content-Type'] = 'application/x-www-form-urlencoded';
            }
        } elseif ($json !== null) {
            $options[RequestOptions::JSON] = $json;
        }

        return $options;
    }

    /**
     * @return array<string,mixed>
     */
    private function buildBytesOptions(bool $authenticated, string $accept): array
    {
        $options = [
            RequestOptions::AUTH => null,
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::TIMEOUT => $this->options->timeout,
            RequestOptions::ALLOW_REDIRECTS => false,
            RequestOptions::HEADERS => $this->headers(),
        ];
        $options[RequestOptions::HEADERS]['Accept'] = $accept;
        if ($authenticated) {
            $options[RequestOptions::AUTH] = [$this->options->accountSid, $this->options->apiKey];
        }

        return $options;
    }

    /**
     * Send with the documented retry policy and return the final response.
     *
     * @param array<string,mixed> $options
     */
    private function send(string $method, string $url, array $options): ResponseInterface
    {
        $idempotent = in_array(strtoupper($method), self::IDEMPOTENT_METHODS, true);

        for ($attempt = 0; $attempt <= $this->options->maxRetries; $attempt++) {
            try {
                $response = $this->http->request($method, $url, $options);
            } catch (NetworkExceptionInterface $e) {
                // No response exists: Guzzle 8 NetworkException family (ConnectException,
                // ConnectTimeoutException, NetworkTimeoutException) and Guzzle 7 ConnectException.
                $this->handleTransportError($method, $e, $idempotent, $attempt);
                continue;
            } catch (RequestException $e) {
                // Guzzle 8 reclassifies failures after response headers were received
                // as ResponseTransferException (e.g. ResponseTimeoutException). Those
                // carry a *partial* response which must not be parsed as a completed
                // one — surface them as transport errors instead.
                if (self::isResponseTransferFailure($e)) {
                    $this->handleTransportError($method, $e, $idempotent, $attempt);
                    continue;
                }
                // Guzzle 7: a RequestException may carry a response (then use it).
                // Guzzle 8: only the ResponseException family exposes one;
                // RequestException itself has no getResponse().
                $response = null;
                if (method_exists($e, 'getResponse')) {
                    $response = $e->getResponse();
                }
                if ($response === null) {
                    // Request failed before a response existed (e.g. request-body or
                    // framing failures). Only retried for idempotent methods.
                    $this->handleTransportError($method, $e, $idempotent, $attempt);
                    continue;
                }
            } catch (GuzzleException $e) {
                $this->handleTransportError($method, $e, $idempotent, $attempt);
                continue;
            }

            $status = $response->getStatusCode();
            $statusRetryable = in_array($status, self::RETRYABLE_STATUSES, true)
                && ($idempotent || $status === 429);
            if ($statusRetryable && $attempt < $this->options->maxRetries) {
                $retryAfter = self::parseRetryAfterHeader($response);
                if ($retryAfter !== null && $retryAfter > self::MAX_RETRY_AFTER_SECONDS) {
                    // Do not sleep and do not retry: surface the error immediately,
                    // with the parsed Retry-After attached.
                    return $response;
                }
                $response->getBody()->close();
                $this->sleepBackoff($attempt, $retryAfter);
                continue;
            }

            return $response;
        }

        throw new ApiException('retry loop exhausted', statusCode: 0);
    }

    /**
     * Decide whether a transport-level failure should be retried, sleep the
     * backoff delay, or (on the final attempt or a never-retryable failure)
     * raise the SDK transport error. Non-idempotent methods are only retried
     * when the failure provably happened before the request was sent.
     */
    private function handleTransportError(string $method, \Throwable $e, bool $idempotent, int $attempt): void
    {
        $retryable = $idempotent || self::isConnectionEstablishmentFailure($e);
        if ($attempt >= $this->options->maxRetries || !$retryable) {
            throw new ApiException(
                sprintf('transport error after %d attempt(s): %s', $attempt + 1, $e->getMessage()),
                statusCode: 0,
                previous: $e,
            );
        }
        $this->sleepBackoff($attempt, null);
    }

    /**
     * True when the failure happened while establishing the connection, i.e. the
     * request provably was not sent and a POST may safely be retried: DNS failure,
     * connection refused, connect-phase timeout, TLS handshake failure.
     */
    private static function isConnectionEstablishmentFailure(\Throwable $e): bool
    {
        if (class_exists(NetworkTimeoutException::class)) {
            // Guzzle 8: the exception hierarchy is explicit. ConnectException
            // (with ConnectTimeoutException) means establishment failed;
            // NetworkTimeoutException is a transfer timeout and is not a
            // ConnectException, so it falls through as not safe.
            return $e instanceof ConnectException;
        }

        // Guzzle 7 folds connection and transfer failures into ConnectException;
        // the cURL error number in the message disambiguates.
        if (!$e instanceof ConnectException) {
            return false;
        }
        $message = $e->getMessage();
        if (preg_match('/cURL error (?:6|7|35|60)\b/', $message) === 1) {
            return true;
        }

        return str_contains($message, 'cURL error 28') && str_contains($message, 'Connection timed out');
    }

    /**
     * True when the exception represents a failed transfer after response
     * headers were received (Guzzle 8 ResponseTransferException family,
     * including ResponseTimeoutException). Its response is partial and must
     * not be treated as a completed exchange.
     */
    private static function isResponseTransferFailure(\Throwable $e): bool
    {
        return class_exists(\GuzzleHttp\Exception\ResponseTransferException::class)
            && $e instanceof \GuzzleHttp\Exception\ResponseTransferException;
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
        return (string) UriResolver::resolve(new Uri($this->baseUrl . '/'), new Uri($path));
    }

    /**
     * Resolve a page link (`next_page_uri` / `meta.next_page_url`) to a path this
     * transport can request. Relative URIs resolve against the transport's scheme
     * and host; absolute URIs must match it or an {@see ApiException} is raised.
     */
    private function resolvePageUri(string $uri): string
    {
        $resolved = $this->absUrl($uri);
        if (!$this->isSameOrigin($this->baseUrl, $resolved)) {
            throw new ApiException(
                'refusing to follow a page link to a different host: ' . $uri,
                statusCode: 0,
            );
        }

        return $resolved;
    }

    private function resolveRedirect(string $currentUrl, string $location): string
    {
        $target = UriResolver::resolve(new Uri($currentUrl), new Uri($location));
        if (!in_array($target->getScheme(), ['https', 'http'], true) || $target->getUserInfo() !== '') {
            throw new ApiException('invalid redirect target', statusCode: 0);
        }
        return (string) $target;
    }

    /**
     * @return array{scheme:string,host:string,port:int}
     */
    private static function urlOriginParts(string $url): array
    {
        $parts = parse_url($url);

        return [
            'scheme' => $parts['scheme'] ?? 'https',
            'host' => strtolower($parts['host'] ?? ''),
            'port' => $parts['port'] ?? (($parts['scheme'] ?? 'https') === 'https' ? 443 : 80),
        ];
    }

    private static function isSameOrigin(string $a, string $b): bool
    {
        $pa = self::urlOriginParts($a);
        $pb = self::urlOriginParts($b);

        return $pa === $pb;
    }

    private static function isSchemeDowngrade(string $from, string $to): bool
    {
        return (self::urlOriginParts($from)['scheme'] === 'https')
            && (self::urlOriginParts($to)['scheme'] === 'http');
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
                    'could not decode response body as JSON: ' . substr($bodyText, 0, 200),
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
        $moreInfo = null;
        if (is_array($body)) {
            if (isset($body['code']) && (is_int($body['code']) || is_string($body['code']))) {
                $code = $body['code'];
            }
            if (isset($body['message']) && is_string($body['message']) && $body['message'] !== '') {
                $message = $body['message'];
            }
            if (isset($body['more_info']) && is_string($body['more_info']) && $body['more_info'] !== '') {
                $moreInfo = $body['more_info'];
            }
        }

        $retryAfter = self::parseRetryAfterHeader($response);

        throw self::makeApiException($status, $code, $body, $message, $moreInfo, $retryAfter);
    }

    private static function makeApiException(
        int $status,
        int|string|null $code,
        mixed $body,
        string $message,
        ?string $moreInfo,
        ?float $retryAfter = null,
    ): ApiException {
        return match (true) {
            $status === 400 => new BadRequestException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 401 => new AuthenticationException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 403 => new PermissionDeniedException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 404 => new NotFoundException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 409 => new ConflictException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 410 => new GoneException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 429 => new RateLimitException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status === 501 => new NotImplementedApiException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            $status >= 500 && $status < 600 => new ServerException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
            default => new ApiException($message, $status, $code, $body, null, $moreInfo, $retryAfter),
        };
    }

    /**
     * Render query params as a query string. Null values are dropped, booleans
     * become "true"/"false", and list values are emitted as repeated keys
     * (`Key=a&Key=b`), never as `Key[0]=…`.
     *
     * @param array<string,mixed> $params
     */
    public static function buildQueryString(array $params): string
    {
        return implode('&', self::encodePairs($params));
    }

    /**
     * Render a form body. Same value rules as {@see buildQueryString()}.
     *
     * @param array<string,mixed> $form
     */
    public static function buildFormBody(array $form): string
    {
        return implode('&', self::encodePairs($form));
    }

    /**
     * @param array<string,mixed> $params
     *
     * @return list<string>
     */
    private static function encodePairs(array $params): array
    {
        $pairs = [];
        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }
            $encodedKey = rawurlencode((string) $key);
            if (is_array($value)) {
                foreach ($value as $item) {
                    if ($item === null) {
                        continue;
                    }
                    $pairs[] = $encodedKey . '=' . rawurlencode(self::scalarValue($item));
                }
                continue;
            }
            $pairs[] = $encodedKey . '=' . rawurlencode(self::scalarValue($value));
        }

        return $pairs;
    }

    private static function scalarValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        return (string) $value;
    }

    private function sleepBackoff(int $attempt, ?float $retryAfterSeconds): void
    {
        $delay = self::backoffSeconds($attempt, $retryAfterSeconds);
        if ($delay > 0.0) {
            usleep((int) round($delay * 1_000_000));
        }
    }

    /**
     * Exponential backoff with full jitter (0.5s base, 8s cap). An explicit
     * Retry-After delay (already capped by the caller) wins over the backoff.
     */
    private static function backoffSeconds(int $attempt, ?float $retryAfterSeconds): float
    {
        if ($retryAfterSeconds !== null) {
            return min(self::MAX_RETRY_AFTER_SECONDS, $retryAfterSeconds);
        }
        $ceiling = min(self::BACKOFF_MAX_SECONDS, self::BACKOFF_BASE_SECONDS * (2 ** $attempt));

        return mt_rand(0, (int) round($ceiling * 1_000)) / 1_000;
    }

    /**
     * Parse a Retry-After header as delta-seconds or HTTP-date. Returns the
     * delay in seconds from now, or null when absent/unparseable.
     */
    private static function parseRetryAfterHeader(ResponseInterface $response): ?float
    {
        if (!$response->hasHeader('Retry-After')) {
            return null;
        }
        $value = trim($response->getHeaderLine('Retry-After'));
        if ($value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return max(0.0, (float) $value);
        }
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return max(0.0, (float) ($timestamp - time()));
    }
}
