<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/SourceIpMappings/{Sid}`. Only SipDomainSid is mutable. */
final class UpdateVoiceV1SourceIpMappingRequest
{
    public function __construct(
        public readonly string $sipDomainSid,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return ['SipDomainSid' => $this->sipDomainSid];
    }
}
