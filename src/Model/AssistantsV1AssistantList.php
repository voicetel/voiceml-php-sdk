<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Assistants` response. */
final class AssistantsV1AssistantList implements Model
{
    /** @param list<AssistantsV1Assistant> $assistants */
    public function __construct(
        public readonly array $assistants,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['assistants'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Assistant::fromArray($row);
            }
        }
        return new self(
            assistants: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
