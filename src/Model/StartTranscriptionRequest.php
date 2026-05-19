<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Transcriptions`.
 */
final class StartTranscriptionRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $track = null,
        public readonly ?string $languageCode = null,
        public readonly ?string $transcriptionEngine = null,
        public readonly ?bool $profanityFilter = null,
        public readonly ?bool $partialResults = null,
        public readonly ?string $hints = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?string $statusCallbackEvents = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Name' => 'name',
            'Track' => 'track',
            'LanguageCode' => 'languageCode',
            'TranscriptionEngine' => 'transcriptionEngine',
            'ProfanityFilter' => 'profanityFilter',
            'PartialResults' => 'partialResults',
            'Hints' => 'hints',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
            'StatusCallbackEvents' => 'statusCallbackEvents',
        ];
    }
}
