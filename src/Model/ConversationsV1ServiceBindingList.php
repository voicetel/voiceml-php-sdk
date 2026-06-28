<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Bindings` response. */
final class ConversationsV1ServiceBindingList implements Model
{
    /** @param list<ConversationsV1ServiceBinding> $bindings */
    public function __construct(
        public readonly array $bindings,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['bindings'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceBinding::fromArray($row);
        }
        return new self(
            bindings: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
