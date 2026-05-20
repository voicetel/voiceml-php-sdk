<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A DID assigned to the authenticated tenant.
 *
 * Twilio-shape: {@see $sid} is the canonical `PN`-prefixed opaque identifier (34 chars) and
 * {@see $phoneNumber} carries the E.164 form. They are distinct fields — never substitute
 * one for the other in URLs.
 *
 * The spec defines a much wider Twilio-compat field set (capabilities, regulatory,
 * SMS-channel, emergency, trunking) that VoiceML emits with default values to keep
 * strict-binding SDK deserialisers happy. This model surfaces the fields that VoiceML
 * actually tracks; extra wire fields are silently ignored on deserialisation.
 */
final class IncomingPhoneNumber implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $phoneNumber,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
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
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            voiceUrl: isset($data['voice_url']) ? (string) $data['voice_url'] : null,
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
