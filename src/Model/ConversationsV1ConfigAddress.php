<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Configuration Address — Twilio Conversations v1 `IG…` resource. */
final class ConversationsV1ConfigAddress implements Model
{
    /** @param array<string,mixed>|null $autoCreation */
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly ?string $type = null,
        public readonly ?string $address = null,
        public readonly ?string $friendlyName = null,
        public readonly ?array $autoCreation = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
        public readonly ?string $addressCountry = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            address: isset($data['address']) ? (string) $data['address'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            autoCreation: isset($data['auto_creation']) && is_array($data['auto_creation'])
                ? $data['auto_creation']
                : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            addressCountry: isset($data['address_country']) ? (string) $data['address_country'] : null,
        );
    }
}
