<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * One tripped check from the `/health` deep probe.
 */
final class HealthFailure implements Model
{
    public function __construct(
        public readonly string $check,
        public readonly string $detail,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            check: (string) ($data['check'] ?? ''),
            detail: (string) ($data['detail'] ?? ''),
        );
    }
}
