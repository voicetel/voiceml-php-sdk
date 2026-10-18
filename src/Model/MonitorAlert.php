<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A monitor alert (`GET /v1/Alerts`).
 */
final class MonitorAlert implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $alertText,
        public readonly string $errorCode,
        public readonly string $logLevel,
        public readonly string $moreInfo,
        public readonly string $requestMethod,
        public readonly string $requestUrl,
        public readonly string $url,
        public readonly string $dateCreated,
        public readonly string $dateGenerated,
        public readonly string $dateUpdated,
        public readonly ?string $apiVersion = null,
        public readonly ?string $resourceSid = null,
        public readonly ?string $serviceSid = null,
        public readonly ?string $requestHeaders = null,
        public readonly ?string $requestVariables = null,
        public readonly ?string $responseBody = null,
        public readonly ?string $responseHeaders = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            alertText: (string) ($data['alert_text'] ?? ''),
            errorCode: (string) ($data['error_code'] ?? ''),
            logLevel: (string) ($data['log_level'] ?? ''),
            moreInfo: (string) ($data['more_info'] ?? ''),
            requestMethod: (string) ($data['request_method'] ?? ''),
            requestUrl: (string) ($data['request_url'] ?? ''),
            url: (string) ($data['url'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateGenerated: (string) ($data['date_generated'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            apiVersion: isset($data['api_version']) ? (string) $data['api_version'] : null,
            resourceSid: isset($data['resource_sid']) ? (string) $data['resource_sid'] : null,
            serviceSid: isset($data['service_sid']) ? (string) $data['service_sid'] : null,
            requestHeaders: isset($data['request_headers']) ? (string) $data['request_headers'] : null,
            requestVariables: isset($data['request_variables']) ? (string) $data['request_variables'] : null,
            responseBody: isset($data['response_body']) ? (string) $data['response_body'] : null,
            responseHeaders: isset($data['response_headers']) ? (string) $data['response_headers'] : null,
        );
    }
}
