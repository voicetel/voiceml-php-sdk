<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `/v1/Knowledge/{id}/…` — knowledge-scoped sub-resource tree. Bound to a
 * parent KnowledgeId; produced via {@see AssistantsV1Resource::knowledge()}.
 *
 *  - `status` — `/v1/Knowledge/{id}/Status` (read-only)
 *  - `chunks` — `/v1/Knowledge/{id}/Chunks` (read-only paginated)
 */
final class AssistantsV1KnowledgeScopeResource
{
    public readonly AssistantsV1KnowledgeStatusResource $status;
    public readonly AssistantsV1KnowledgeChunksResource $chunks;

    public function __construct(Transport $transport, string $knowledgeId)
    {
        $this->status = new AssistantsV1KnowledgeStatusResource($transport, $knowledgeId);
        $this->chunks = new AssistantsV1KnowledgeChunksResource($transport, $knowledgeId);
    }
}
