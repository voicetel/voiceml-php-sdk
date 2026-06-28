<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Conversations role — Twilio Conversations v1 `RL…` resource. */
final class ConversationsV1Role implements Model
{
    /** @param list<string>|null $permissions */
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly string $type,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $friendlyName = null,
        public readonly ?array $permissions = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $perms = null;
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $perms = array_values(array_map(static fn ($v): string => (string) $v, $data['permissions']));
        }
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            type: (string) ($data['type'] ?? ''),
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            permissions: $perms,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
