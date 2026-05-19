<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Streams/{Sid}`.
 */
final class StopStreamRequest extends FormRequest
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
