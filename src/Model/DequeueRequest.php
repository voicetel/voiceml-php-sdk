<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Queues/{sid}/Members/Front` and `/Members/{CallSid}`.
 */
final class DequeueRequest extends FormRequest
{
    public function __construct(
        public readonly string $url,
        public readonly ?string $method = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Url' => 'url',
            'Method' => 'method',
        ];
    }
}
