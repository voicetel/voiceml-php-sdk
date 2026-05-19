<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Queues`. Idempotent on `FriendlyName`.
 */
final class CreateQueueRequest extends FormRequest
{
    public function __construct(
        public readonly string $friendlyName,
        public readonly ?int $maxSize = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'FriendlyName' => 'friendlyName',
            'MaxSize' => 'maxSize',
        ];
    }
}
