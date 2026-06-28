<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `/v1/Knowledge/{id}/Status` — ingestion status singleton. */
final class AssistantsV1KnowledgeStatus implements Model
{
    public function __construct(
        public readonly ?string $accountSid,
        public readonly string $status,
        public readonly ?string $lastStatus = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            status: (string) ($data['status'] ?? ''),
            lastStatus: isset($data['last_status']) ? (string) $data['last_status'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
