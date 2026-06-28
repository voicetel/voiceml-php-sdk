<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Conversations user — Twilio Conversations v1 `US…` resource. */
final class ConversationsV1User implements Model
{
    /** @param array<string,string>|null $links */
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $roleSid = null,
        public readonly ?string $identity = null,
        public readonly ?string $friendlyName = null,
        public readonly ?string $attributes = null,
        public readonly ?bool $isOnline = null,
        public readonly ?bool $isNotifiable = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            roleSid: isset($data['role_sid']) ? (string) $data['role_sid'] : null,
            identity: isset($data['identity']) ? (string) $data['identity'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            attributes: isset($data['attributes']) ? (string) $data['attributes'] : null,
            isOnline: isset($data['is_online']) ? (bool) $data['is_online'] : null,
            isNotifiable: isset($data['is_notifiable']) ? (bool) $data['is_notifiable'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
        );
    }
}
