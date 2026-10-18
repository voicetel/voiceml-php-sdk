<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1KnowledgeChunkList;
use VoiceML\Transport;

/** `/v1/Knowledge/{id}/Chunks` — read-only paginated chunks list. */
final class AssistantsV1KnowledgeChunksResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $knowledgeId,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1KnowledgeChunkList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Knowledge', $this->knowledgeId, 'Chunks'), $query);
        return AssistantsV1KnowledgeChunkList::fromArray($raw);
    }
}
