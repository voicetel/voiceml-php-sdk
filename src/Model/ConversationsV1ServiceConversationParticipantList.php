<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Participants` response. */
final class ConversationsV1ServiceConversationParticipantList implements Model
{
    /** @param list<ConversationsV1ServiceConversationParticipant> $participants */
    public function __construct(
        public readonly array $participants,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['participants'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceConversationParticipant::fromArray($row);
        }
        return new self(
            participants: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
