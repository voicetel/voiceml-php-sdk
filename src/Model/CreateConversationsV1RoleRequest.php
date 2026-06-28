<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Roles`. */
final class CreateConversationsV1RoleRequest
{
    /** @param list<string> $permission */
    public function __construct(
        public readonly string $friendlyName,
        public readonly string $type,
        public readonly array $permission,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return [
            'FriendlyName' => $this->friendlyName,
            'Type' => $this->type,
            'Permission' => $this->permission,
        ];
    }
}
