<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * The authenticated Account resource (`GET /2010-04-01/Accounts/{Sid}`).
 */
final class Account implements Model
{
    /**
     * @param array<string,string>|null $subresourceUris
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $status,
        public readonly string $type,
        public readonly ?string $friendlyName = null,
        public readonly ?string $ownerAccountSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $uri = null,
        public readonly ?array $subresourceUris = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,string>|null $subresourceUris */
        $subresourceUris = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
            : null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            ownerAccountSid: isset($data['owner_account_sid']) ? (string) $data['owner_account_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
            subresourceUris: $subresourceUris,
        );
    }
}
