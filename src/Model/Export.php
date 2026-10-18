<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A day's export bundle listing (`GET /Exports`).
 */
final class Export implements Model
{
    public function __construct(
        public readonly string $date,
        public readonly string $size,
        public readonly string $uri,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            date: (string) ($data['date'] ?? ''),
            size: (string) ($data['size'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
