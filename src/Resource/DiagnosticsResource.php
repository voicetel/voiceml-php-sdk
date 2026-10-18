<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\HealthStatus;
use VoiceML\Transport;

/**
 * Diagnostic surfaces — `/health` and the OpenAPI doc endpoints.
 *
 * These don't sit under `/2010-04-01/Accounts/{AccountSid}/…`; they're
 * mounted at the server root and marked `security: []` in the spec, so this
 * resource issues un-authenticated requests through the normal
 * {@see Transport} — the caller's HTTP client, timeout, proxy and User-Agent
 * all apply, a 503 health failure raises
 * {@see \VoiceML\Exception\ServerException}, and no credentials are needed.
 */
final class DiagnosticsResource extends Resource
{
    /**
     * Standalone, credential-free entry point: `$diag = DiagnosticsResource::forBaseUrl(...)`.
     */
    public static function forBaseUrl(
        ?string $baseUrl = null,
        ?\GuzzleHttp\ClientInterface $httpClient = null,
        ?float $timeout = null,
    ): self {
        return new self(Transport::forDiagnostics($baseUrl, $httpClient, $timeout));
    }

    /**
     * Hit `/health`. 200 = all hard checks pass; 503 raises
     * {@see \VoiceML\Exception\ServerException} with the failure list on `body`.
     * `warnings` / `failures` are optional — a bare `{"ok":true}` decodes fine.
     */
    public function health(): HealthStatus
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestUnauthenticated('GET', '/health');
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
        $raw = $this->transport->requestUnauthenticated('GET', '/openapi.json');
        return $raw;
    }

    /**
     * Fetch the OpenAPI spec as raw YAML text (`/openapi.yaml`).
     */
    public function openapiYaml(): string
    {
        $result = $this->transport->fetchBytes('/openapi.yaml', 'text/yaml, */*', authenticated: false);

        return $result['body'];
    }

    /**
     * Fetch the OpenAPI spec as raw YAML text (`/openapi.yml`).
     */
    public function openapiYml(): string
    {
        $result = $this->transport->fetchBytes('/openapi.yml', 'text/yaml, */*', authenticated: false);

        return $result['body'];
    }
}
