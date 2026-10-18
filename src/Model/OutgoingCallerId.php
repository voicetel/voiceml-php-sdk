<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A verified outgoing caller ID (`/OutgoingCallerIds`).
 */
final class OutgoingCallerId implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $phoneNumber,
        public readonly string $friendlyName,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            phoneNumber: (string) ($data['phone_number'] ?? ''),
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
