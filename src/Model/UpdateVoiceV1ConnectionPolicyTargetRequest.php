<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/ConnectionPolicies/{Sid}/Targets/{Sid}`. All fields optional. */
final class UpdateVoiceV1ConnectionPolicyTargetRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $target = null,
        public readonly ?int $priority = null,
        public readonly ?int $weight = null,
        public readonly ?bool $enabled = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->target !== null) $out['Target'] = $this->target;
        if ($this->priority !== null) $out['Priority'] = $this->priority;
        if ($this->weight !== null) $out['Weight'] = $this->weight;
        if ($this->enabled !== null) $out['Enabled'] = $this->enabled;
        return $out;
    }
}
