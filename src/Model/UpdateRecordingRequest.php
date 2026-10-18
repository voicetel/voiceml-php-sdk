<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Recordings/{rsid}` — stop / pause / resume.
 * `pauseBehavior` controls how a paused recording resumes (`skip-silence`,
 * `silent`, …); it is only meaningful while `status` is `paused`.
 */
final class UpdateRecordingRequest extends FormRequest
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
