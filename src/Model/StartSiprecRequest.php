<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Siprec`.
 */
final class StartSiprecRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $connectorName = null,
        public readonly ?string $track = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Name' => 'name',
            'ConnectorName' => 'connectorName',
            'Track' => 'track',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
        ];
    }
}
