<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Knowledge source (`aia_know_…`). */
final class AssistantsV1Knowledge implements Model
{
    /** @param array<string,mixed>|null $knowledgeSourceDetails */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $type,
        public readonly ?string $description,
        public readonly ?string $status,
        public readonly ?string $embeddingModel,
        public readonly ?array $knowledgeSourceDetails = null,
        public readonly ?string $url = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            description: isset($data['description']) ? (string) $data['description'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            embeddingModel: isset($data['embedding_model']) ? (string) $data['embedding_model'] : null,
            knowledgeSourceDetails: isset($data['knowledge_source_details']) && is_array($data['knowledge_source_details'])
                ? $data['knowledge_source_details']
                : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
