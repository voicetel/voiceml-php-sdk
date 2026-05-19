<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class Queue implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $friendlyName,
        public readonly int $currentSize,
        public readonly int $maxSize,
        public readonly int $averageWaitTime,
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
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            currentSize: (int) ($data['current_size'] ?? 0),
            maxSize: (int) ($data['max_size'] ?? 0),
            averageWaitTime: (int) ($data['average_wait_time'] ?? 0),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
