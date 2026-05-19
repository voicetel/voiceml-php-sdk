<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class SiprecSession implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $callSid,
        public readonly string $status,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly ?string $name = null,
        public readonly ?string $connectorName = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
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
            callSid: (string) ($data['call_sid'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            name: isset($data['name']) ? (string) $data['name'] : null,
            connectorName: isset($data['connector_name']) ? (string) $data['connector_name'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
