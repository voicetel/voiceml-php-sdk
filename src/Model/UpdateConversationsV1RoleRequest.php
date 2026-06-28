<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Roles/{Sid}`. */
final class UpdateConversationsV1RoleRequest
{
    /** @param list<string> $permission */
    public function __construct(
        public readonly array $permission,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return ['Permission' => $this->permission];
    }
}
