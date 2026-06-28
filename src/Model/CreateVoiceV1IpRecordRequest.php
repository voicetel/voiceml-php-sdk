<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/IpRecords`. */
final class CreateVoiceV1IpRecordRequest
{
    public function __construct(
        public readonly string $ipAddress,
        public readonly ?string $friendlyName = null,
        public readonly ?int $cidrPrefixLength = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['IpAddress' => $this->ipAddress];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->cidrPrefixLength !== null) $out['CidrPrefixLength'] = $this->cidrPrefixLength;
        return $out;
    }
}
