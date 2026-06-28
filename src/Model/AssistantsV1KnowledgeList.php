<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Knowledge` response. The array key is `knowledge` (singular). */
final class AssistantsV1KnowledgeList implements Model
{
    /** @param list<AssistantsV1Knowledge> $knowledge */
    public function __construct(
        public readonly array $knowledge,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['knowledge'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Knowledge::fromArray($row);
            }
        }
        return new self(
            knowledge: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
