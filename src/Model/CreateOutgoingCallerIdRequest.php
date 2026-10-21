<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /OutgoingCallerIds` — starts a validation call to
 * `phoneNumber`; the caller must read back the returned validation code.
 */
final class CreateOutgoingCallerIdRequest extends FormRequest
{
    public function __construct(
        public readonly string $phoneNumber,
        public readonly ?string $friendlyName = null,
        public readonly ?int $callDelay = null,
        public readonly ?string $extension = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'PhoneNumber' => 'phoneNumber',
            'FriendlyName' => 'friendlyName',
            'CallDelay' => 'callDelay',
            'Extension' => 'extension',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
        ];
    }
}
