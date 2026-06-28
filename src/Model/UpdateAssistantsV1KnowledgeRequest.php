<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `PUT /v1/Knowledge/{id}`. JSON wire format. All fields optional. */
final class UpdateAssistantsV1KnowledgeRequest
{
    /** @param array<string,mixed>|null $knowledgeSourceDetails */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $type = null,
        public readonly ?string $description = null,
        public readonly ?string $embeddingModel = null,
        public readonly ?array $knowledgeSourceDetails = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->name !== null) {
            $out['name'] = $this->name;
        }
        if ($this->type !== null) {
            $out['type'] = $this->type;
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
