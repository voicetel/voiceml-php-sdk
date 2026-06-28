<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Knowledge/{id}/Chunks` response. */
final class AssistantsV1KnowledgeChunkList implements Model
{
    /** @param list<AssistantsV1KnowledgeChunk> $chunks */
    public function __construct(
        public readonly array $chunks,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['chunks'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1KnowledgeChunk::fromArray($row);
            }
        }
        return new self(
            chunks: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
