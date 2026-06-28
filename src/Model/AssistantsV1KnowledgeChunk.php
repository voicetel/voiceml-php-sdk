<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A single chunk returned by `/v1/Knowledge/{id}/Chunks`. */
final class AssistantsV1KnowledgeChunk implements Model
{
    /** @param array<string,mixed>|null $metadata */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $content,
        public readonly ?array $metadata = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            content: isset($data['content']) ? (string) $data['content'] : null,
            metadata: isset($data['metadata']) && is_array($data['metadata']) ? $data['metadata'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
