<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Account-wide Conversations configuration — `/v1/Configuration`. */
final class ConversationsV1Configuration implements Model
{
    /** @param array<string,string>|null $links */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $defaultChatServiceSid = null,
        public readonly ?string $defaultMessagingServiceSid = null,
        public readonly ?string $defaultInactiveTimer = null,
        public readonly ?string $defaultClosedTimer = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            defaultChatServiceSid: isset($data['default_chat_service_sid']) ? (string) $data['default_chat_service_sid'] : null,
            defaultMessagingServiceSid: isset($data['default_messaging_service_sid']) ? (string) $data['default_messaging_service_sid'] : null,
            defaultInactiveTimer: isset($data['default_inactive_timer']) ? (string) $data['default_inactive_timer'] : null,
            defaultClosedTimer: isset($data['default_closed_timer']) ? (string) $data['default_closed_timer'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
        );
    }
}
