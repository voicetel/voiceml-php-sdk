<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Messages/{Sid}/Feedback` — reports the delivery outcome
 * of a message back to the API.
 */
final class CreateMessageFeedbackRequest extends FormRequest
{
    /**
     * @param 'confirmed'|'unconfirmed'|string $outcome
     */
    public function __construct(
        public readonly string $outcome,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Outcome' => 'outcome',
        ];
    }
}
