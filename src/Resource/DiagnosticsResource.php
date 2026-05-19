<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use VoiceML\Exception\ApiException;
use VoiceML\Model\HealthStatus;
use VoiceML\Transport;

/**
 * Diagnostic surfaces — `/health` and the OpenAPI doc endpoints.
 *
 * These don't sit under `/2010-04-01/Accounts/{AccountSid}/…`; they're mounted at the server
 * root and don't require auth (the spec marks them `security: []`). For that reason this
 * resource issues its own un-authed Guzzle calls rather than going through the main
 * {@see Transport}.
 */
final class DiagnosticsResource
{
    private readonly Transport $transport;
    private readonly ClientInterface $http;

    public function __construct(Transport $transport, ?ClientInterface $httpClient = null)
    {
        $this->transport = $transport;
        $this->http = $httpClient ?? new GuzzleClient([
            'base_uri' => $transport->baseUrl() . '/',
            'timeout' => 10.0,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);
    }

    /**
     * Hit `/health`. 200 = all hard checks pass; 503 raises {@see \VoiceML\Exception\ServerException}
     * with the failure list on `body`.
     */
    public function health(): HealthStatus
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->unauthRequest('GET', '/health');
        return HealthStatus::fromArray($raw);
    }

    /**
     * Fetch the OpenAPI spec as parsed JSON.
     *
     * @return array<string,mixed>
     */
    public function openapiJson(): array
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->unauthRequest('GET', '/openapi.json');
        return $raw;
    }

    private function unauthRequest(string $method, string $path): mixed
    {
        try {
            $response = $this->http->request($method, $this->transport->baseUrl() . $path, [
                RequestOptions::HEADERS => ['Accept' => 'application/json'],
                RequestOptions::HTTP_ERRORS => false,
            ]);
        } catch (GuzzleException $e) {
            throw new ApiException(
                'transport error fetching diagnostic resource: ' . $e->getMessage(),
                statusCode: 0,
                previous: $e,
            );
        }

        $status = $response->getStatusCode();
        $bodyText = (string) $response->getBody();
        if ($status < 200 || $status >= 300) {
            throw new ApiException(
                sprintf('HTTP %d on %s', $status, $path),
                statusCode: $status,
                body: $bodyText,
            );
        }
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
}
