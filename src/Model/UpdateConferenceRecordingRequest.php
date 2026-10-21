<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Conferences/{sid}/Recordings/{rsid}` — stop / pause / resume.
 * `pauseBehavior` mirrors the call-scoped {@see UpdateRecordingRequest}: with
 * `status` = `paused`, `skip` leaves the paused span out of the recording while
 * `silence` (the default) keeps it as silence.
 */
final class UpdateConferenceRecordingRequest extends FormRequest
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $pauseBehavior = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Status' => 'status',
            'PauseBehavior' => 'pauseBehavior',
        ];
    }
}
