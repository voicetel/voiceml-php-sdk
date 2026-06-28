<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Per-service Webhook configuration — `/v1/Services/{ChatServiceSid}/Configuration/Webhooks`. */
final class ConversationsV1ServiceWebhookConfiguration implements Model
{
    /** @param list<string>|null $filters */
    public function __construct(
        public readonly string $method,
        public readonly ?string $accountSid = null,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $preWebhookUrl = null,
        public readonly ?string $postWebhookUrl = null,
        public readonly ?array $filters = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $filters = null;
        if (isset($data['filters']) && is_array($data['filters'])) {
            $filters = array_values(array_map(static fn ($v): string => (string) $v, $data['filters']));
        }
        return new self(
            method: (string) ($data['method'] ?? ''),
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            preWebhookUrl: isset($data['pre_webhook_url']) ? (string) $data['pre_webhook_url'] : null,
            postWebhookUrl: isset($data['post_webhook_url']) ? (string) $data['post_webhook_url'] : null,
            filters: $filters,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
