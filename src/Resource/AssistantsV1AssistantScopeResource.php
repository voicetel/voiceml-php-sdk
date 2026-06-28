<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `/v1/Assistants/{id}/…` — assistant-scoped sub-resource tree. Bound to a
 * parent AssistantId; produced via {@see AssistantsV1Resource::assistants()}.
 *
 * Sub-resource map:
 *  - `tools`      — `/v1/Assistants/{id}/Tools` (list + attach + detach)
 *  - `knowledge`  — `/v1/Assistants/{id}/Knowledge` (list + attach + detach)
 *  - `feedbacks`  — `/v1/Assistants/{id}/Feedbacks` (list + create)
 *  - `messages`   — `/v1/Assistants/{id}/Messages` (POST send-message)
 */
final class AssistantsV1AssistantScopeResource
{
    public readonly AssistantsV1AssistantToolsResource $tools;
    public readonly AssistantsV1AssistantKnowledgeResource $knowledge;
    public readonly AssistantsV1AssistantFeedbacksResource $feedbacks;
    public readonly AssistantsV1AssistantMessagesResource $messages;

    public function __construct(Transport $transport, string $assistantId)
    {
        $this->tools = new AssistantsV1AssistantToolsResource($transport, $assistantId);
        $this->knowledge = new AssistantsV1AssistantKnowledgeResource($transport, $assistantId);
        $this->feedbacks = new AssistantsV1AssistantFeedbacksResource($transport, $assistantId);
        $this->messages = new AssistantsV1AssistantMessagesResource($transport, $assistantId);
    }
}
