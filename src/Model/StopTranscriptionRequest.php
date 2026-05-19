<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Transcriptions/{Sid}`.
 */
final class StopTranscriptionRequest extends FormRequest
{
    public function __construct(
        public readonly string $status = 'stopped',
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Status' => 'status',
        ];
    }
}
