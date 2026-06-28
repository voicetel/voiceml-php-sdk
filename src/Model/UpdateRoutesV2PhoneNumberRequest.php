<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v2/PhoneNumbers/{PhoneNumber}`. */
final class UpdateRoutesV2PhoneNumberRequest
{
    public function __construct(
        public readonly ?string $voiceRegion = null,
        public readonly ?string $friendlyName = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->voiceRegion !== null) $out['VoiceRegion'] = $this->voiceRegion;
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        return $out;
    }
}
