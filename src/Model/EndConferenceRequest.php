<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Conferences/{sid}`. v1 supports only `Status=completed`.
 */
final class EndConferenceRequest extends FormRequest
{
    public function __construct(
        public readonly string $status = 'completed',
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Status' => 'status',
        ];
    }
}
