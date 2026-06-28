<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Conversations/{ConversationSid}/Messages` response. */
final class ConversationsV1ConversationMessageList implements Model
{
    /** @param list<ConversationsV1ConversationMessage> $messages */
    public function __construct(
        public readonly array $messages,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['messages'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ConversationMessage::fromArray($row);
        }
        return new self(
            messages: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
