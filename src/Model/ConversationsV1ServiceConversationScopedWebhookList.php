<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Webhooks` response. */
final class ConversationsV1ServiceConversationScopedWebhookList implements Model
{
    /** @param list<ConversationsV1ServiceConversationScopedWebhook> $webhooks */
    public function __construct(
        public readonly array $webhooks,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['webhooks'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceConversationScopedWebhook::fromArray($row);
        }
        return new self(
            webhooks: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
