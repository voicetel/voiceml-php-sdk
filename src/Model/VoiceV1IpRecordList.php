<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/IpRecords` response. */
final class VoiceV1IpRecordList implements Model
{
    /** @param list<VoiceV1IpRecord> $ipRecords */
    public function __construct(
        public readonly array $ipRecords,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['ip_records'] ?? []) as $row) {
            if (is_array($row)) $items[] = VoiceV1IpRecord::fromArray($row);
        }
        return new self(
            ipRecords: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
