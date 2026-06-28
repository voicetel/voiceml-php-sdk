<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A ConnectionPolicy Target — Twilio Voice v1 `NE…` resource. */
final class VoiceV1ConnectionPolicyTarget implements Model
{
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $connectionPolicySid,
        public readonly ?string $sid,
        public readonly int $priority,
        public readonly int $weight,
        public readonly ?string $friendlyName = null,
        public readonly ?string $target = null,
        public readonly ?bool $enabled = null,
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
            connectionPolicySid: isset($data['connection_policy_sid']) ? (string) $data['connection_policy_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            priority: (int) ($data['priority'] ?? 0),
            weight: (int) ($data['weight'] ?? 0),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            target: isset($data['target']) ? (string) $data['target'] : null,
            enabled: isset($data['enabled']) ? (bool) $data['enabled'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
