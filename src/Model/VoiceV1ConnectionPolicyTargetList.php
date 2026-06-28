<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/ConnectionPolicies/{Sid}/Targets` response. */
final class VoiceV1ConnectionPolicyTargetList implements Model
{
    /** @param list<VoiceV1ConnectionPolicyTarget> $targets */
    public function __construct(
        public readonly array $targets,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['targets'] ?? []) as $row) {
            if (is_array($row)) $items[] = VoiceV1ConnectionPolicyTarget::fromArray($row);
        }
        return new self(
            targets: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
