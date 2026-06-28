<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Maps an IpRecord to a SIP Domain — Twilio Voice v1 `IB…` resource. */
final class VoiceV1SourceIpMapping implements Model
{
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $ipRecordSid = null,
        public readonly ?string $sipDomainSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            ipRecordSid: isset($data['ip_record_sid']) ? (string) $data['ip_record_sid'] : null,
            sipDomainSid: isset($data['sip_domain_sid']) ? (string) $data['sip_domain_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
