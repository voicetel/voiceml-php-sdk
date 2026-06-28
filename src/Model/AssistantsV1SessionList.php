<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Sessions` response. */
final class AssistantsV1SessionList implements Model
{
    /** @param list<AssistantsV1Session> $sessions */
    public function __construct(
        public readonly array $sessions,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['sessions'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Session::fromArray($row);
            }
        }
        return new self(
            sessions: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
