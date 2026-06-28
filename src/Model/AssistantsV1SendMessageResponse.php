<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Response from `POST /v1/Assistants/{id}/Messages`. */
final class AssistantsV1SendMessageResponse implements Model
{
    public function __construct(
        public readonly string $status,
        public readonly string $sessionId,
        public readonly ?string $accountSid,
        public readonly ?bool $flagged = null,
        public readonly ?bool $aborted = null,
        public readonly ?string $body = null,
        public readonly ?string $error = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            status: (string) ($data['status'] ?? ''),
            sessionId: (string) ($data['session_id'] ?? ''),
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            flagged: isset($data['flagged']) ? (bool) $data['flagged'] : null,
            aborted: isset($data['aborted']) ? (bool) $data['aborted'] : null,
            body: isset($data['body']) ? (string) $data['body'] : null,
            error: isset($data['error']) ? (string) $data['error'] : null,
        );
    }
}
