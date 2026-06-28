<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/ByocTrunks/{Sid}`. All fields optional; only set fields change. */
final class UpdateVoiceV1ByocTrunkRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $statusCallbackUrl = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?bool $cnamLookupEnabled = null,
        public readonly ?string $connectionPolicySid = null,
        public readonly ?string $fromDomainSid = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->voiceUrl !== null) $out['VoiceUrl'] = $this->voiceUrl;
        if ($this->voiceMethod !== null) $out['VoiceMethod'] = $this->voiceMethod;
        if ($this->voiceFallbackUrl !== null) $out['VoiceFallbackUrl'] = $this->voiceFallbackUrl;
        if ($this->voiceFallbackMethod !== null) $out['VoiceFallbackMethod'] = $this->voiceFallbackMethod;
        if ($this->statusCallbackUrl !== null) $out['StatusCallbackUrl'] = $this->statusCallbackUrl;
        if ($this->statusCallbackMethod !== null) $out['StatusCallbackMethod'] = $this->statusCallbackMethod;
        if ($this->cnamLookupEnabled !== null) $out['CnamLookupEnabled'] = $this->cnamLookupEnabled;
        if ($this->connectionPolicySid !== null) $out['ConnectionPolicySid'] = $this->connectionPolicySid;
        if ($this->fromDomainSid !== null) $out['FromDomainSid'] = $this->fromDomainSid;
        return $out;
    }
}
