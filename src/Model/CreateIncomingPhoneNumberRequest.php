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
        ];
    }
}
