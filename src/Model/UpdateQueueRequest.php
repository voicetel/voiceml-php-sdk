<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Queues/{sid}`. `maxSize` of `0` means unlimited (Twilio default).
 */
final class UpdateQueueRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
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
