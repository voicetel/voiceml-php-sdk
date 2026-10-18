<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /2010-04-01/Accounts/{Sid}`. Partial — only set fields are
 * touched. A 409 (20409) indicates an optimistic-lock conflict: re-fetch,
 * reconcile, and retry.
 */
final class UpdateAccountRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $sttFsVendor = null,
        public readonly ?string $sttHttpUrl = null,
        public readonly ?string $sttHttpToken = null,
        public readonly ?string $ttsHttpUrl = null,
        public readonly ?string $ttsHttpToken = null,
        public readonly ?string $ttsEngineName = null,
        public readonly ?string $ttsDefaultVoice = null,
        public readonly ?string $ttsDefaultLanguage = null,
        public readonly ?string $awsBucket = null,
        public readonly ?string $awsRegion = null,
        public readonly ?string $awsPrefix = null,
        public readonly ?string $awsAccessKeyId = null,
        #[\SensitiveParameter]
        public readonly ?string $awsSecretAccessKey = null,
        public readonly ?string $awsKmsKeyId = null,
        public readonly ?string $allowCidr = null,
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
            'SttFsVendor' => 'sttFsVendor',
            'SttHttpUrl' => 'sttHttpUrl',
            'SttHttpToken' => 'sttHttpToken',
            'TtsHttpUrl' => 'ttsHttpUrl',
            'TtsHttpToken' => 'ttsHttpToken',
            'TtsEngineName' => 'ttsEngineName',
            'TtsDefaultVoice' => 'ttsDefaultVoice',
            'TtsDefaultLanguage' => 'ttsDefaultLanguage',
            'AwsBucket' => 'awsBucket',
            'AwsRegion' => 'awsRegion',
            'AwsPrefix' => 'awsPrefix',
            'AwsAccessKeyId' => 'awsAccessKeyId',
            'AwsSecretAccessKey' => 'awsSecretAccessKey',
            'AwsKmsKeyId' => 'awsKmsKeyId',
            'AllowCidr' => 'allowCidr',
        ];
    }
}
