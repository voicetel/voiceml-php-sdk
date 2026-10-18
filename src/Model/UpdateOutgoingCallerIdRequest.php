<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /OutgoingCallerIds/{sid}` — only the friendly name is settable.
 */
final class UpdateOutgoingCallerIdRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'FriendlyName' => 'friendlyName',
        ];
    }
}
