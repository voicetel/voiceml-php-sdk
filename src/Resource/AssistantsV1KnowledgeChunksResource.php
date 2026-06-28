<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1KnowledgeChunkList;
use VoiceML\Transport;

/** `/v1/Knowledge/{id}/Chunks` — read-only paginated chunks list. */
final class AssistantsV1KnowledgeChunksResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $knowledgeId,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1KnowledgeChunkList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Knowledge/{$this->knowledgeId}/Chunks", $query);
        return AssistantsV1KnowledgeChunkList::fromArray($raw);
    }
}
