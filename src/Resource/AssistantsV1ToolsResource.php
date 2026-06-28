<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1Tool;
use VoiceML\Model\AssistantsV1ToolList;
use VoiceML\Model\AssistantsV1ToolWithPolicies;
use VoiceML\Model\CreateAssistantsV1ToolRequest;
use VoiceML\Model\UpdateAssistantsV1ToolRequest;
use VoiceML\Transport;

/** `/v1/Tools` — Assistants v1 top-level Tool CRUD. JSON request bodies. */
final class AssistantsV1ToolsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateAssistantsV1ToolRequest $body */
    public function create(array|CreateAssistantsV1ToolRequest $body): AssistantsV1Tool
    {
        $json = $body instanceof CreateAssistantsV1ToolRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Tools', null, null, $json);
        return AssistantsV1Tool::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1ToolList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Tools', $query);
        return AssistantsV1ToolList::fromArray($raw);
    }

    public function fetch(string $toolId): AssistantsV1ToolWithPolicies
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Tools/{$toolId}");
        return AssistantsV1ToolWithPolicies::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateAssistantsV1ToolRequest $body */
    public function update(string $toolId, array|UpdateAssistantsV1ToolRequest $body = []): AssistantsV1Tool
    {
        $json = $body instanceof UpdateAssistantsV1ToolRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('PUT', "/v1/Tools/{$toolId}", null, null, $json);
        return AssistantsV1Tool::fromArray($raw);
    }

    public function delete(string $toolId): void
    {
        $this->transport->request('DELETE', "/v1/Tools/{$toolId}");
    }
}
