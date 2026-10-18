<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Conferences/{sid}/Recordings/{rsid}` — stop only. The
 * call-scoped {@see UpdateRecordingRequest} additionally carries
 * `PauseBehavior`, which this route does not accept.
 */
final class UpdateConferenceRecordingRequest extends FormRequest
{
    public function __construct(
        public readonly string $status,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Status' => 'status',
        ];
    }
}
