<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /v1/Services/{sid}/PhoneNumbers` — adds a number to the
 * Messaging Service's sender pool.
 */
final class AddMessagingServicePhoneNumberRequest extends FormRequest
{
    public function __construct(
        public readonly string $phoneNumberSid,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'PhoneNumberSid' => 'phoneNumberSid',
        ];
    }
}
