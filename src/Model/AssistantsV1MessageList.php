<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Sessions/{id}/Messages` response. */
final class AssistantsV1MessageList implements Model
{
    /** @param list<AssistantsV1Message> $messages */
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
            if (is_array($row)) {
                $items[] = AssistantsV1Message::fromArray($row);
            }
        }
        return new self(
            messages: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
