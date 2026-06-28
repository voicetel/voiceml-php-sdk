<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Settings`. */
final class UpdateVoiceV1DialingPermissionsSettingsRequest
{
    public function __construct(
        public readonly ?bool $dialingPermissionsInheritance = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->dialingPermissionsInheritance !== null) {
            $out['DialingPermissionsInheritance'] = $this->dialingPermissionsInheritance;
        }
        return $out;
    }
}
