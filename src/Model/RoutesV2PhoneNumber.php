<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Twilio routes/v2 Inbound Processing Region binding for a phone number.
 * SID is `QQ…`. Keyed by the E.164 phone number (or its `PN…` sid).
 */
final class RoutesV2PhoneNumber implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $phoneNumber,
        public readonly string $accountSid,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceRegion = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            phoneNumber: (string) ($data['phone_number'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            voiceRegion: isset($data['voice_region']) ? (string) $data['voice_region'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
