<?php

declare(strict_types=1);

namespace VoiceML\Model;

use RuntimeException;

/**
 * Twilio-compat sub-object on {@see IncomingPhoneNumber}. Indicates which channel
 * types a DID supports. All four flags are REQUIRED in the spec — a missing field
 * is a protocol error and causes {@see fromArray()} to throw.
 *
 * VoiceML is voice-only — production responses emit `voice=true` and the rest `false`.
 * The full set is modelled so strict-binding SDK parity tests can introspect each flag.
 */
final class IncomingPhoneNumberCapabilities implements Model
{
    public function __construct(
        public readonly bool $voice,
        public readonly bool $sms,
        public readonly bool $mms,
        public readonly bool $fax,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['voice', 'sms', 'mms', 'fax'] as $key) {
            if (!array_key_exists($key, $data)) {
                throw new RuntimeException(
                    "IncomingPhoneNumberCapabilities: missing required field '{$key}'",
                );
            }
        }

        return new self(
            voice: (bool) $data['voice'],
            sms: (bool) $data['sms'],
            mms: (bool) $data['mms'],
            fax: (bool) $data['fax'],
        );
    }
}
