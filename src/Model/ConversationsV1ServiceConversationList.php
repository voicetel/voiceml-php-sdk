<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Conversations` response. */
final class ConversationsV1ServiceConversationList implements Model
{
    /** @param list<ConversationsV1ServiceConversation> $conversations */
    public function __construct(
        public readonly array $conversations,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['conversations'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceConversation::fromArray($row);
        }
        return new self(
            conversations: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
