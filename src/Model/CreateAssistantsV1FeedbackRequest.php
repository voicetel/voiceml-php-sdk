<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Assistants/{id}/Feedbacks`. JSON wire format. */
final class CreateAssistantsV1FeedbackRequest
{
    public function __construct(
        public readonly string $sessionId,
        public readonly ?string $messageId = null,
        public readonly ?float $score = null,
        public readonly ?string $text = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['session_id' => $this->sessionId];
        if ($this->messageId !== null) {
            $out['message_id'] = $this->messageId;
        }
        if ($this->score !== null) {
            $out['score'] = $this->score;
        }
        if ($this->text !== null) {
            $out['text'] = $this->text;
        }
        return $out;
    }
}
