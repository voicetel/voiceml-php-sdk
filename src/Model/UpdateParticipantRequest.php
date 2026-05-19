<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Conferences/{sid}/Participants/{CallSid}`.
 *
 * At least one of `muted` / `hold` must be set.
 */
final class UpdateParticipantRequest extends FormRequest
{
    public function __construct(
        public readonly ?bool $muted = null,
        public readonly ?bool $hold = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Muted' => 'muted',
            'Hold' => 'hold',
        ];
    }
}
