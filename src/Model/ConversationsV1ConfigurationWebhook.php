<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Account-global Conversations webhook config — `/v1/Configuration/Webhooks`. */
final class ConversationsV1ConfigurationWebhook implements Model
{
    /** @param list<string>|null $filters */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly string $method,
        public readonly string $target,
        public readonly ?array $filters = null,
        public readonly ?string $preWebhookUrl = null,
        public readonly ?string $postWebhookUrl = null,
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
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            method: (string) ($data['method'] ?? ''),
            target: (string) ($data['target'] ?? ''),
            filters: $filters,
            preWebhookUrl: isset($data['pre_webhook_url']) ? (string) $data['pre_webhook_url'] : null,
            postWebhookUrl: isset($data['post_webhook_url']) ? (string) $data['post_webhook_url'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
