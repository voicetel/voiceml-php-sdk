<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class QueueMember implements Model
{
    public function __construct(
        public readonly string $callSid,
        public readonly string $queueSid,
        public readonly string $accountSid,
        public readonly string $dateEnqueued,
        public readonly int $waitTime,
        public readonly int $position,
        public readonly string $uri,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            callSid: (string) ($data['call_sid'] ?? ''),
            queueSid: (string) ($data['queue_sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            dateEnqueued: (string) ($data['date_enqueued'] ?? ''),
            waitTime: (int) ($data['wait_time'] ?? 0),
            position: (int) ($data['position'] ?? 0),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
