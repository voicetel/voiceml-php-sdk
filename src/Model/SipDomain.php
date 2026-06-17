<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A SIP ingress domain — Twilio-compatible `SD…` resource. */
final class SipDomain implements Model
{
    /** @param array<string,string>|null $subresourceUris */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $domainName,
        public readonly string $apiVersion,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $friendlyName = null,
        public readonly ?string $authType = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $voiceStatusCallbackUrl = null,
        public readonly ?string $voiceStatusCallbackMethod = null,
        public readonly ?bool $sipRegistration = null,
        public readonly ?bool $emergencyCallingEnabled = null,
        public readonly ?bool $secure = null,
        public readonly ?string $byocTrunkSid = null,
        public readonly ?string $emergencyCallerSid = null,
        public readonly ?array $subresourceUris = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            domainName: (string) ($data['domain_name'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            authType: isset($data['auth_type']) ? (string) $data['auth_type'] : null,
            voiceUrl: isset($data['voice_url']) ? (string) $data['voice_url'] : null,
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            voiceStatusCallbackUrl: isset($data['voice_status_callback_url']) ? (string) $data['voice_status_callback_url'] : null,
            voiceStatusCallbackMethod: isset($data['voice_status_callback_method']) ? (string) $data['voice_status_callback_method'] : null,
            sipRegistration: isset($data['sip_registration']) ? (bool) $data['sip_registration'] : null,
            emergencyCallingEnabled: isset($data['emergency_calling_enabled']) ? (bool) $data['emergency_calling_enabled'] : null,
            secure: isset($data['secure']) ? (bool) $data['secure'] : null,
            byocTrunkSid: isset($data['byoc_trunk_sid']) ? (string) $data['byoc_trunk_sid'] : null,
            emergencyCallerSid: isset($data['emergency_caller_sid']) ? (string) $data['emergency_caller_sid'] : null,
            subresourceUris: isset($data['subresource_uris']) && is_array($data['subresource_uris'])
                ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
                : null,
        );
    }
}
