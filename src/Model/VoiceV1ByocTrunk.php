<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Bring-your-own-carrier trunk — Twilio Voice v1 `BY…` resource. */
final class VoiceV1ByocTrunk implements Model
{
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $sid,
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $statusCallbackUrl = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?bool $cnamLookupEnabled = null,
        public readonly ?string $connectionPolicySid = null,
        public readonly ?string $fromDomainSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            voiceUrl: isset($data['voice_url']) ? (string) $data['voice_url'] : null,
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            statusCallbackUrl: isset($data['status_callback_url']) ? (string) $data['status_callback_url'] : null,
            statusCallbackMethod: isset($data['status_callback_method']) ? (string) $data['status_callback_method'] : null,
            cnamLookupEnabled: isset($data['cnam_lookup_enabled']) ? (bool) $data['cnam_lookup_enabled'] : null,
            connectionPolicySid: isset($data['connection_policy_sid']) ? (string) $data['connection_policy_sid'] : null,
            fromDomainSid: isset($data['from_domain_sid']) ? (string) $data['from_domain_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
