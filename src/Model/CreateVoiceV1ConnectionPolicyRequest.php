<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/ConnectionPolicies`. */
final class CreateVoiceV1ConnectionPolicyRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        return $out;
    }
}
