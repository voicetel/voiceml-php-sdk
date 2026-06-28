<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Service-scoped Conversation — Twilio Conversations v1 `CH…` resource under `/v1/Services/{ChatServiceSid}`. */
final class ConversationsV1ServiceConversation implements Model
{
    /**
     * @param array<string,mixed>|null $timers
     * @param array<string,string>|null $links
     * @param array<string,mixed>|null $bindings
     */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $sid,
        public readonly string $state,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $messagingServiceSid = null,
        public readonly ?string $friendlyName = null,
        public readonly ?string $uniqueName = null,
        public readonly ?string $attributes = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?array $timers = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
        public readonly ?array $bindings = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            state: (string) ($data['state'] ?? ''),
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            messagingServiceSid: isset($data['messaging_service_sid']) ? (string) $data['messaging_service_sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            uniqueName: isset($data['unique_name']) ? (string) $data['unique_name'] : null,
            attributes: isset($data['attributes']) ? (string) $data['attributes'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            timers: isset($data['timers']) && is_array($data['timers']) ? $data['timers'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
            bindings: isset($data['bindings']) && is_array($data['bindings']) ? $data['bindings'] : null,
        );
    }
}
