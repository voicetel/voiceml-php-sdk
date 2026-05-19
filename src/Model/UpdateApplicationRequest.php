<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Applications/{sid}`. Partial — only set fields are touched.
 */
final class UpdateApplicationRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?bool $voiceCallerIdLookup = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?string $statusCallbackEvent = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'FriendlyName' => 'friendlyName',
            'VoiceUrl' => 'voiceUrl',
            'VoiceMethod' => 'voiceMethod',
            'VoiceFallbackUrl' => 'voiceFallbackUrl',
            'VoiceFallbackMethod' => 'voiceFallbackMethod',
            'VoiceCallerIdLookup' => 'voiceCallerIdLookup',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
            'StatusCallbackEvent' => 'statusCallbackEvent',
        ];
    }
}
