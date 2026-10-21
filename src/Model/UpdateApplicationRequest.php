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
        public readonly ?string $messageStatusCallback = null,
        public readonly ?string $smsStatusCallback = null,
        public readonly ?string $smsUrl = null,
        public readonly ?string $smsMethod = null,
        public readonly ?string $smsFallbackUrl = null,
        public readonly ?string $smsFallbackMethod = null,
        public readonly ?bool $publicApplicationConnectEnabled = null,
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
            'MessageStatusCallback' => 'messageStatusCallback',
            'SmsStatusCallback' => 'smsStatusCallback',
            'SmsUrl' => 'smsUrl',
            'SmsMethod' => 'smsMethod',
            'SmsFallbackUrl' => 'smsFallbackUrl',
            'SmsFallbackMethod' => 'smsFallbackMethod',
            'PublicApplicationConnectEnabled' => 'publicApplicationConnectEnabled',
        ];
    }
}
