<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Twilio-compatible sub-object on {@see IncomingPhoneNumber}. Indicates which channel
 * types a DID supports. Absent or null fields decode as `false` (or `null` for fax,
 * which only the top-level IncomingPhoneNumber shapes carry) — the server may
 * legitimately emit `capabilities: null` or partial objects, and reading a flag
 * must never crash hydration.
 *
 * VoiceML is voice-only — production responses emit `voice=true` and the rest `false`.
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
        return new self(
            voice: self::boolOrDefault($data, 'voice'),
            sms: self::boolOrDefault($data, 'sms'),
            mms: self::boolOrDefault($data, 'mms'),
            fax: array_key_exists('fax', $data) && $data['fax'] !== null ? (bool) $data['fax'] : null,
        );
    }

    /** @param array<string,mixed> $data */
    private static function boolOrDefault(array $data, string $key): bool
    {
        return array_key_exists($key, $data) && $data[$key] !== null ? (bool) $data[$key] : false;
    }
}
