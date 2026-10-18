<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `POST /Messages/{Sid}/Feedback` response.
 */
final class MessageFeedback implements Model
{
    public function __construct(
        public readonly string $accountSid,
        public readonly string $messageSid,
        public readonly string $outcome,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: (string) ($data['account_sid'] ?? ''),
            messageSid: (string) ($data['message_sid'] ?? ''),
            outcome: (string) ($data['outcome'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
