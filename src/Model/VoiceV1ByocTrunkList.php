<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/ByocTrunks` response. */
final class VoiceV1ByocTrunkList implements Model
{
    /** @param list<VoiceV1ByocTrunk> $byocTrunks */
    public function __construct(
        public readonly array $byocTrunks,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['byoc_trunks'] ?? []) as $row) {
            if (is_array($row)) $items[] = VoiceV1ByocTrunk::fromArray($row);
        }
        return new self(
            byocTrunks: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
