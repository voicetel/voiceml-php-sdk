<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A standalone allowed source IP — Twilio Voice v1 `IL…` resource. */
final class VoiceV1IpRecord implements Model
{
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $sid,
        public readonly int $cidrPrefixLength,
        public readonly ?string $friendlyName = null,
        public readonly ?string $ipAddress = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            cidrPrefixLength: (int) ($data['cidr_prefix_length'] ?? 0),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            ipAddress: isset($data['ip_address']) ? (string) $data['ip_address'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
