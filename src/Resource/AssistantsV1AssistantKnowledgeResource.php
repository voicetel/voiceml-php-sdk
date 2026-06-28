<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1KnowledgeList;
use VoiceML\Transport;

/**
 * `/v1/Assistants/{id}/Knowledge` — assistant-scoped knowledge attachments.
 * Bound to a parent AssistantId.
 */
final class AssistantsV1AssistantKnowledgeResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $assistantId,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1KnowledgeList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Assistants/{$this->assistantId}/Knowledge", $query);
        return AssistantsV1KnowledgeList::fromArray($raw);
    }

    public function attach(string $knowledgeId): void
    {
        $this->transport->request('POST', "/v1/Assistants/{$this->assistantId}/Knowledge/{$knowledgeId}");
    }

    public function detach(string $knowledgeId): void
    {
        $this->transport->request('DELETE', "/v1/Assistants/{$this->assistantId}/Knowledge/{$knowledgeId}");
    }
}
