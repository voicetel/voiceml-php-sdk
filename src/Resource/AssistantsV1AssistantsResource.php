<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1Assistant;
use VoiceML\Model\AssistantsV1AssistantList;
use VoiceML\Model\AssistantsV1AssistantWithToolsAndKnowledge;
use VoiceML\Model\CreateAssistantsV1AssistantRequest;
use VoiceML\Model\UpdateAssistantsV1AssistantRequest;

/** `/v1/Assistants` — Assistants v1 top-level CRUD. JSON request bodies. */
final class AssistantsV1AssistantsResource extends Resource
{

    /** @param array<string,mixed>|CreateAssistantsV1AssistantRequest $body */
    public function create(array|CreateAssistantsV1AssistantRequest $body): AssistantsV1Assistant
    {
        $json = $body instanceof CreateAssistantsV1AssistantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Assistants'), null, null, $json);
        return AssistantsV1Assistant::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1AssistantList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Assistants'), $query);
        return AssistantsV1AssistantList::fromArray($raw);
    }

    public function fetch(string $assistantId): AssistantsV1AssistantWithToolsAndKnowledge
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Assistants', $assistantId));
        return AssistantsV1AssistantWithToolsAndKnowledge::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateAssistantsV1AssistantRequest $body */
    public function update(string $assistantId, array|UpdateAssistantsV1AssistantRequest $body = []): AssistantsV1Assistant
    {
        $json = $body instanceof UpdateAssistantsV1AssistantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('PUT', $this->v1Path('Assistants', $assistantId), null, null, $json);
        return AssistantsV1Assistant::fromArray($raw);
    }

    public function delete(string $assistantId): void
    {
        $this->transport->request('DELETE', $this->v1Path('Assistants', $assistantId));
    }
}
