<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Streams`. `url` is the wss:// endpoint.
 */
final class StartStreamRequest extends FormRequest
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $track = null,
        public readonly ?string $name = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Url' => 'url',
            'Track' => 'track',
            'Name' => 'name',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
        ];
    }
}
