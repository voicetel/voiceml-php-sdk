<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for the typed Local/Mobile/TollFree IncomingPhoneNumbers creates
 * (`POST /IncomingPhoneNumbers/{Local,Mobile,TollFree}`). Those routes only
 * accept `PhoneNumber`, `VoiceUrl` and `VoiceMethod` — the full-field
 * {@see CreateIncomingPhoneNumberRequest} is for the untyped create.
 */
final class CreateTypedIncomingPhoneNumberRequest extends FormRequest
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly ?string $voiceUrl = null,
        public readonly ?string $voiceMethod = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'PhoneNumber' => 'phoneNumber',
            'VoiceUrl' => 'voiceUrl',
            'VoiceMethod' => 'voiceMethod',
        ];
    }
}
