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
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'PhoneNumber' => 'phoneNumber',
            'FriendlyName' => 'friendlyName',
        ];
    }
}
