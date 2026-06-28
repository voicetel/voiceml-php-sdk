<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Feedback (`aia_fdbk_…`) — user rating of a Message. */
final class AssistantsV1Feedback implements Model
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $accountSid,
        public readonly ?string $userSid,
        public readonly ?string $assistantId,
        public readonly ?string $sessionId,
        public readonly ?string $messageId,
        public readonly ?float $score,
        public readonly ?string $text,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            userSid: isset($data['user_sid']) ? (string) $data['user_sid'] : null,
            assistantId: isset($data['assistant_id']) ? (string) $data['assistant_id'] : null,
            sessionId: isset($data['session_id']) ? (string) $data['session_id'] : null,
            messageId: isset($data['message_id']) ? (string) $data['message_id'] : null,
            score: isset($data['score']) ? (float) $data['score'] : null,
            text: isset($data['text']) ? (string) $data['text'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
