<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Knowledge`. JSON wire format. */
final class CreateAssistantsV1KnowledgeRequest
{
    /** @param array<string,mixed>|null $knowledgeSourceDetails */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly ?string $assistantId = null,
        public readonly ?string $description = null,
        public readonly ?string $embeddingModel = null,
        public readonly ?array $knowledgeSourceDetails = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [
            'name' => $this->name,
            'type' => $this->type,
        ];
        if ($this->assistantId !== null) {
            $out['assistant_id'] = $this->assistantId;
        }
        if ($this->description !== null) {
            $out['description'] = $this->description;
        }
        if ($this->embeddingModel !== null) {
            $out['embedding_model'] = $this->embeddingModel;
        }
        if ($this->knowledgeSourceDetails !== null) {
            $out['knowledge_source_details'] = $this->knowledgeSourceDetails;
        }
        return $out;
    }
}
