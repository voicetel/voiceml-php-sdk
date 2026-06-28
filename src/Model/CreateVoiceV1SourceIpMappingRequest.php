<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/SourceIpMappings`. */
final class CreateVoiceV1SourceIpMappingRequest
{
    public function __construct(
        public readonly string $ipRecordSid,
        public readonly string $sipDomainSid,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return [
            'IpRecordSid' => $this->ipRecordSid,
            'SipDomainSid' => $this->sipDomainSid,
        ];
    }
}
