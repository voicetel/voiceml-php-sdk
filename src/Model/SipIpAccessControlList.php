<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A named bag of CIDR-bound IPs — `AL…`. */
final class SipIpAccessControlList implements Model
{
    /** @param array<string,string>|null $subresourceUris */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $friendlyName = null,
        public readonly ?array $subresourceUris = null,
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
            subresourceUris: isset($data['subresource_uris']) && is_array($data['subresource_uris'])
                ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
                : null,
        );
    }
}
