<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Users`. */
final class CreateConversationsV1UserRequest
{
    public function __construct(
        public readonly string $identity,
        public readonly ?string $friendlyName = null,
        public readonly ?string $attributes = null,
        public readonly ?string $roleSid = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['Identity' => $this->identity];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        if ($this->roleSid !== null) $out['RoleSid'] = $this->roleSid;
        return $out;
    }
}
