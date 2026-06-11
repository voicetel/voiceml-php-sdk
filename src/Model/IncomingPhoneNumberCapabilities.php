<?php

declare(strict_types=1);

namespace VoiceML\Model;

use RuntimeException;

/**
 * Twilio-compatible sub-object on {@see IncomingPhoneNumber}. Indicates which channel
 * types a DID supports. `voice`/`sms`/`mms` are REQUIRED — a missing field is a
 * protocol error and causes {@see fromArray()} to throw. `fax` is OPTIONAL/nullable:
 * Twilio's canonical Local/Mobile/TollFree list responses omit it entirely (only the
 * top-level IncomingPhoneNumber Create/Fetch/List shapes carry it). `null` means the
 * field was absent on the wire; do not conflate with `false`.
 *
 * VoiceML is voice-only — production responses emit `voice=true` and the rest `false`.
 * The full set is modelled so strict-binding SDK conformance tests can introspect each flag.
 */
final class IncomingPhoneNumberCapabilities implements Model
{
    public function __construct(
        public readonly bool $voice,
        public readonly bool $sms,
        public readonly bool $mms,
        public readonly ?bool $fax,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        foreach (['voice', 'sms', 'mms'] as $key) {
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
            fax: array_key_exists('fax', $data) && $data['fax'] !== null ? (bool) $data['fax'] : null,
        );
    }
}
