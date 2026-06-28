<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/SourceIpMappings` response. */
final class VoiceV1SourceIpMappingList implements Model
{
    /** @param list<VoiceV1SourceIpMapping> $sourceIpMappings */
    public function __construct(
        public readonly array $sourceIpMappings,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['source_ip_mappings'] ?? []) as $row) {
            if (is_array($row)) $items[] = VoiceV1SourceIpMapping::fromArray($row);
        }
        return new self(
            sourceIpMappings: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
