<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Siprec/{Sid}`.
 *
 * Clears VoiceML's session tracking only — the SRS recording itself continues until call
 * hangup (documented mod_siprec limitation).
 */
final class StopSiprecRequest extends FormRequest
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
