<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /health` response — composite probe.
 *
 * Hard-check failures flip `ok` to false (server returns 503). Soft-check warnings surface
 * in `warnings` only and don't take the host out of rotation.
 */
final class HealthStatus implements Model
{
    /**
     * @param list<HealthFailure> $warnings
     * @param list<HealthFailure> $failures
     */
    public function __construct(
        public readonly bool $ok,
        public readonly array $warnings = [],
        public readonly array $failures = [],
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<HealthFailure> $warnings */
        $warnings = [];
        if (isset($data['warnings']) && is_array($data['warnings'])) {
            foreach ($data['warnings'] as $row) {
                if (is_array($row)) {
                    $warnings[] = HealthFailure::fromArray($row);
                }
            }
        }
        /** @var list<HealthFailure> $failures */
        $failures = [];
        if (isset($data['failures']) && is_array($data['failures'])) {
            foreach ($data['failures'] as $row) {
                if (is_array($row)) {
                    $failures[] = HealthFailure::fromArray($row);
                }
            }
        }
        return new self(
            ok: (bool) ($data['ok'] ?? false),
            warnings: $warnings,
            failures: $failures,
        );
    }
}
