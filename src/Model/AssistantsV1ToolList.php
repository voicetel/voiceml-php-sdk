<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Tools` response. */
final class AssistantsV1ToolList implements Model
{
    /** @param list<AssistantsV1Tool> $tools */
    public function __construct(
        public readonly array $tools,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['tools'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Tool::fromArray($row);
            }
        }
        return new self(
            tools: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
