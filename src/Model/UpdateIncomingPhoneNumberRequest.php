<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /IncomingPhoneNumbers/{sid}`. Partial — only set fields are touched.
 */
final class UpdateIncomingPhoneNumberRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $smsUrl = null,
        public readonly ?string $smsMethod = null,
        public readonly ?string $smsFallbackUrl = null,
        public readonly ?string $smsFallbackMethod = null,
        public readonly ?string $smsApplicationSid = null,
        public readonly ?string $voiceApplicationSid = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'VoiceUrl' => 'voiceUrl',
            'VoiceMethod' => 'voiceMethod',
            'VoiceFallbackUrl' => 'voiceFallbackUrl',
            'VoiceFallbackMethod' => 'voiceFallbackMethod',
            'SmsUrl' => 'smsUrl',
            'SmsMethod' => 'smsMethod',
            'SmsFallbackUrl' => 'smsFallbackUrl',
            'SmsFallbackMethod' => 'smsFallbackMethod',
            'SmsApplicationSid' => 'smsApplicationSid',
            'VoiceApplicationSid' => 'voiceApplicationSid',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
        ];
    }
}
