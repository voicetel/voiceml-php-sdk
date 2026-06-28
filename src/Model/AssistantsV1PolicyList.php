<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Policies` response. */
final class AssistantsV1PolicyList implements Model
{
    /** @param list<AssistantsV1Policy> $policies */
    public function __construct(
        public readonly array $policies,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['policies'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Policy::fromArray($row);
            }
        }
        return new self(
            policies: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
