<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Users` response. */
final class ConversationsV1ServiceUserList implements Model
{
    /** @param list<ConversationsV1ServiceUser> $users */
    public function __construct(
        public readonly array $users,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['users'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceUser::fromArray($row);
        }
        return new self(
            users: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
