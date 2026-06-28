<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Roles` response. */
final class ConversationsV1RoleList implements Model
{
    /** @param list<ConversationsV1Role> $roles */
    public function __construct(
        public readonly array $roles,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['roles'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1Role::fromArray($row);
        }
        return new self(
            roles: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
