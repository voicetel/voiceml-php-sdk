<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * The account record including a freshly issued API key
 * (`POST /2010-04-01/Accounts/{AccountSid}/RotateKey`). Only RotateKey
 * returns the key material; every other account response omits it.
 */
final class AccountWithKey implements Model
{
    /**
     * @param list<string>|null $phoneRegions
     * @param list<string>|null $allowCidr
     * @param list<string>|null $smsAllowedProviders
     * @param list<string>|null $sttAllowedProviders
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $apiKey,
        public readonly string $sipUsername,
        public readonly bool $disabled,
        public readonly bool $sttHttpTokenSet,
        public readonly bool $ttsHttpTokenSet,
        public readonly bool $stripeSecretKeySet,
        public readonly bool $payEnabled,
        public readonly bool $awsSecretAccessKeySet,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly ?array $phoneRegions = null,
        public readonly ?array $allowCidr = null,
        public readonly ?string $name = null,
        public readonly ?string $sttFsVendor = null,
        public readonly ?string $sttHttpUrl = null,
        public readonly ?string $ttsHttpUrl = null,
        public readonly ?string $ttsEngineName = null,
        public readonly ?string $ttsDefaultVoice = null,
        public readonly ?string $ttsDefaultLanguage = null,
        public readonly ?string $smsProvider = null,
        public readonly ?array $smsAllowedProviders = null,
        public readonly ?array $sttAllowedProviders = null,
        public readonly ?string $sttProvider = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $awsBucket = null,
        public readonly ?string $awsRegion = null,
        public readonly ?string $awsPrefix = null,
        public readonly ?string $awsAccessKeyId = null,
        public readonly ?string $awsKmsKeyId = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<string>|null $strings */
        $strings = null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            apiKey: (string) ($data['api_key'] ?? ''),
            sipUsername: (string) ($data['sip_username'] ?? ''),
            disabled: (bool) ($data['disabled'] ?? false),
            sttHttpTokenSet: (bool) ($data['stt_http_token_set'] ?? false),
            ttsHttpTokenSet: (bool) ($data['tts_http_token_set'] ?? false),
            stripeSecretKeySet: (bool) ($data['stripe_secret_key_set'] ?? false),
            payEnabled: (bool) ($data['pay_enabled'] ?? false),
            awsSecretAccessKeySet: (bool) ($data['aws_secret_access_key_set'] ?? false),
            createdAt: (string) ($data['created_at'] ?? ''),
            updatedAt: (string) ($data['updated_at'] ?? ''),
            phoneRegions: self::stringList($data['phone_regions'] ?? null),
            allowCidr: self::stringList($data['allow_cidr'] ?? null),
            name: isset($data['name']) ? (string) $data['name'] : null,
            sttFsVendor: isset($data['stt_fs_vendor']) ? (string) $data['stt_fs_vendor'] : null,
            sttHttpUrl: isset($data['stt_http_url']) ? (string) $data['stt_http_url'] : null,
            ttsHttpUrl: isset($data['tts_http_url']) ? (string) $data['tts_http_url'] : null,
            ttsEngineName: isset($data['tts_engine_name']) ? (string) $data['tts_engine_name'] : null,
            ttsDefaultVoice: isset($data['tts_default_voice']) ? (string) $data['tts_default_voice'] : null,
            ttsDefaultLanguage: isset($data['tts_default_language']) ? (string) $data['tts_default_language'] : null,
            smsProvider: isset($data['sms_provider']) ? (string) $data['sms_provider'] : null,
            smsAllowedProviders: self::stringList($data['sms_allowed_providers'] ?? null),
            sttAllowedProviders: self::stringList($data['stt_allowed_providers'] ?? null),
            sttProvider: isset($data['stt_provider']) ? (string) $data['stt_provider'] : null,
            voiceUrl: isset($data['voice_url']) ? (string) $data['voice_url'] : null,
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            awsBucket: isset($data['aws_bucket']) ? (string) $data['aws_bucket'] : null,
            awsRegion: isset($data['aws_region']) ? (string) $data['aws_region'] : null,
            awsPrefix: isset($data['aws_prefix']) ? (string) $data['aws_prefix'] : null,
            awsAccessKeyId: isset($data['aws_access_key_id']) ? (string) $data['aws_access_key_id'] : null,
            awsKmsKeyId: isset($data['aws_kms_key_id']) ? (string) $data['aws_kms_key_id'] : null,
        );
    }

    /** @return list<string>|null */
    private static function stringList(mixed $value): ?array
    {
        if (!is_array($value)) {
            return null;
        }

        return array_values(array_map(static fn ($v): string => (string) $v, $value));
    }
}
