<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1Knowledge;
use VoiceML\Model\AssistantsV1KnowledgeList;
use VoiceML\Model\CreateAssistantsV1KnowledgeRequest;
use VoiceML\Model\UpdateAssistantsV1KnowledgeRequest;
use VoiceML\Transport;

/** `/v1/Knowledge` — Assistants v1 top-level Knowledge CRUD. JSON request bodies. */
final class AssistantsV1KnowledgeResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateAssistantsV1KnowledgeRequest $body */
    public function create(array|CreateAssistantsV1KnowledgeRequest $body): AssistantsV1Knowledge
    {
        $json = $body instanceof CreateAssistantsV1KnowledgeRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Knowledge', null, null, $json);
        return AssistantsV1Knowledge::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1KnowledgeList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Knowledge', $query);
        return AssistantsV1KnowledgeList::fromArray($raw);
    }

    public function fetch(string $knowledgeId): AssistantsV1Knowledge
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Knowledge/{$knowledgeId}");
        return AssistantsV1Knowledge::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateAssistantsV1KnowledgeRequest $body */
    public function update(string $knowledgeId, array|UpdateAssistantsV1KnowledgeRequest $body = []): AssistantsV1Knowledge
    {
        $json = $body instanceof UpdateAssistantsV1KnowledgeRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('PUT', "/v1/Knowledge/{$knowledgeId}", null, null, $json);
        return AssistantsV1Knowledge::fromArray($raw);
    }

    public function delete(string $knowledgeId): void
    {
        $this->transport->request('DELETE', "/v1/Knowledge/{$knowledgeId}");
    }
}
