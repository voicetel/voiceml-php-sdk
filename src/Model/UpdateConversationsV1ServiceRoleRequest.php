<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Roles/{Sid}`. */
final class UpdateConversationsV1ServiceRoleRequest
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
