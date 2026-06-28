<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/ConnectionPolicies` response. */
final class VoiceV1ConnectionPolicyList implements Model
{
    /** @param list<VoiceV1ConnectionPolicy> $connectionPolicies */
    public function __construct(
        public readonly array $connectionPolicies,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['connection_policies'] ?? []) as $row) {
            if (is_array($row)) $items[] = VoiceV1ConnectionPolicy::fromArray($row);
        }
        return new self(
            connectionPolicies: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
