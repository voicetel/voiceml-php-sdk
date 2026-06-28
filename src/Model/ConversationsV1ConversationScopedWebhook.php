<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Conversation-scoped webhook — Twilio Conversations v1 `WH…` resource. */
final class ConversationsV1ConversationScopedWebhook implements Model
{
    /** @param array<string,mixed>|null $configuration */
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly ?string $conversationSid,
        public readonly ?string $target = null,
        public readonly ?string $url = null,
        public readonly ?array $configuration = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            target: isset($data['target']) ? (string) $data['target'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            configuration: isset($data['configuration']) && is_array($data['configuration'])
                ? $data['configuration']
                : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
