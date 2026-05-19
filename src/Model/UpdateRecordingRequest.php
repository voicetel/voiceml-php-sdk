<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Recordings/{rsid}` — stop / pause / resume.
 */
final class UpdateRecordingRequest extends FormRequest
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
