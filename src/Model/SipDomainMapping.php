<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Round-trip shape for every domain mapping sub-resource. */
final class SipDomainMapping implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $friendlyName = null,
        public readonly ?string $domainSid = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            domainSid: isset($data['domain_sid']) ? (string) $data['domain_sid'] : null,
        );
    }
}
