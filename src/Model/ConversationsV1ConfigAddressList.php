<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Configuration/Addresses` response. */
final class ConversationsV1ConfigAddressList implements Model
{
    /** @param list<ConversationsV1ConfigAddress> $addresses */
    public function __construct(
        public readonly array $addresses,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['addresses'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ConfigAddress::fromArray($row);
        }
        return new self(
            addresses: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
