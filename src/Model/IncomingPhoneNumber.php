<?php

declare(strict_types=1);

namespace VoiceML\Model;

use RuntimeException;

/**
 * A DID assigned to the authenticated tenant.
 *
 * Twilio-shape: {@see $sid} is the canonical `PN`-prefixed opaque identifier (34 chars) and
 * {@see $phoneNumber} carries the E.164 form. They are distinct fields — never substitute
 * one for the other in URLs.
 *
 * The full Twilio-compat field set (capabilities, regulatory, SMS-channel, emergency,
 * trunking) is surfaced here so strict-binding SDK parity tests can introspect each
 * field. VoiceML emits Twilio-compat defaults — empty string, `false`, or `null` —
 * for the fields it doesn't track; reading e.g. `$ipn->capabilities->voice` therefore
 * behaves identically against VoiceML and Twilio.
 */
final class IncomingPhoneNumber implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $phoneNumber,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly IncomingPhoneNumberCapabilities $capabilities,
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $origin = null,
        public readonly ?bool $beta = null,
        public readonly ?string $type = null,
        public readonly ?string $voiceApplicationSid = null,
        public readonly ?bool $voiceCallerIdLookup = null,
        public readonly ?string $voiceReceiveMode = null,
        public readonly ?string $smsUrl = null,
        public readonly ?string $smsMethod = null,
        public readonly ?string $smsFallbackUrl = null,
        public readonly ?string $smsFallbackMethod = null,
        public readonly ?string $smsApplicationSid = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?string $trunkSid = null,
        public readonly ?string $addressSid = null,
        public readonly ?string $addressRequirements = null,
        public readonly ?string $identitySid = null,
        public readonly ?string $bundleSid = null,
        public readonly ?string $emergencyStatus = null,
        public readonly ?string $emergencyAddressSid = null,
        public readonly ?string $emergencyAddressStatus = null,
        public readonly ?string $status = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['capabilities']) || !is_array($data['capabilities'])) {
            throw new RuntimeException(
                "IncomingPhoneNumber: missing required field 'capabilities'",
            );
        }

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            phoneNumber: (string) ($data['phone_number'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            capabilities: IncomingPhoneNumberCapabilities::fromArray($data['capabilities']),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            voiceUrl: isset($data['voice_url']) ? (string) $data['voice_url'] : null,
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            origin: isset($data['origin']) ? (string) $data['origin'] : null,
            beta: isset($data['beta']) ? (bool) $data['beta'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            voiceApplicationSid: isset($data['voice_application_sid']) ? (string) $data['voice_application_sid'] : null,
            voiceCallerIdLookup: isset($data['voice_caller_id_lookup']) ? (bool) $data['voice_caller_id_lookup'] : null,
            voiceReceiveMode: isset($data['voice_receive_mode']) ? (string) $data['voice_receive_mode'] : null,
            smsUrl: isset($data['sms_url']) ? (string) $data['sms_url'] : null,
            smsMethod: isset($data['sms_method']) ? (string) $data['sms_method'] : null,
            smsFallbackUrl: isset($data['sms_fallback_url']) ? (string) $data['sms_fallback_url'] : null,
            smsFallbackMethod: isset($data['sms_fallback_method']) ? (string) $data['sms_fallback_method'] : null,
            smsApplicationSid: isset($data['sms_application_sid']) ? (string) $data['sms_application_sid'] : null,
            statusCallback: isset($data['status_callback']) ? (string) $data['status_callback'] : null,
            statusCallbackMethod: isset($data['status_callback_method']) ? (string) $data['status_callback_method'] : null,
            trunkSid: isset($data['trunk_sid']) ? (string) $data['trunk_sid'] : null,
            addressSid: isset($data['address_sid']) ? (string) $data['address_sid'] : null,
            addressRequirements: isset($data['address_requirements']) ? (string) $data['address_requirements'] : null,
            identitySid: isset($data['identity_sid']) ? (string) $data['identity_sid'] : null,
            bundleSid: isset($data['bundle_sid']) ? (string) $data['bundle_sid'] : null,
            emergencyStatus: isset($data['emergency_status']) ? (string) $data['emergency_status'] : null,
            emergencyAddressSid: isset($data['emergency_address_sid']) ? (string) $data['emergency_address_sid'] : null,
            emergencyAddressStatus: isset($data['emergency_address_status']) ? (string) $data['emergency_address_status'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
        );
    }
}
