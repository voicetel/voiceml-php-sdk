<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1ToolList;
use VoiceML\Transport;

/**
 * `/v1/Assistants/{id}/Tools` — assistant-scoped tools. Bound to a parent
 * AssistantId. Produced via {@see AssistantsV1Resource::assistants()}.
 */
final class AssistantsV1AssistantToolsResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $assistantId,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1ToolList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Assistants', $this->assistantId, 'Tools'), $query);
        return AssistantsV1ToolList::fromArray($raw);
    }

    public function attach(string $toolId): void
    {
        $this->transport->request('POST', $this->v1Path('Assistants', $this->assistantId, 'Tools', $toolId));
    }

    public function detach(string $toolId): void
    {
        $this->transport->request('DELETE', $this->v1Path('Assistants', $this->assistantId, 'Tools', $toolId));
    }
}
