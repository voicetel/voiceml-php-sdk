<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /IncomingPhoneNumbers`. Idempotent: re-POSTing the same
 * `phoneNumber` for the same tenant rebinds its voice routing rather than erroring.
 * Returns 409 when the number is already claimed by a different account.
 */
final class CreateIncomingPhoneNumberRequest extends FormRequest
{
    public function __construct(
        public readonly string $phoneNumber,
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
            'PhoneNumber' => 'phoneNumber',
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
