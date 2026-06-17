<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A single CIDR-bound entry in an IpAccessControlList — `IP…`. */
final class SipIpAddress implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $ipAccessControlListSid,
        public readonly string $friendlyName,
        public readonly string $ipAddress,
        public readonly int $cidrPrefixLength,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            ipAccessControlListSid: (string) ($data['ip_access_control_list_sid'] ?? ''),
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            ipAddress: (string) ($data['ip_address'] ?? ''),
            cidrPrefixLength: (int) ($data['cidr_prefix_length'] ?? 32),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
