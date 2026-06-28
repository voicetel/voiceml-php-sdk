<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->assistantsV1` — top-level holder for the Assistants v1
 * (assistants.twilio.com/v1) family. Twilio AI-Assistants product surface.
 *
 * Surface map (7 families, 30 ops). Note: `assistants`, `knowledge`, and
 * `sessions` each have BOTH a property (top-level CRUD) AND a method that
 * returns a scope resource for the per-id sub-tree — PHP allows a property
 * and a method to share an identifier.
 *
 *  - `assistants` / `assistants(id)->{tools,knowledge,feedbacks,messages}`
 *  - `tools`
 *  - `knowledge` / `knowledge(id)->{status,chunks}`
 *  - `sessions` / `sessions(id)->messages`
 *  - `policies`
 */
final class AssistantsV1Resource
{
    public readonly AssistantsV1AssistantsResource $assistants;
    public readonly AssistantsV1ToolsResource $tools;
    public readonly AssistantsV1KnowledgeResource $knowledge;
    public readonly AssistantsV1SessionsResource $sessions;
    public readonly AssistantsV1PoliciesResource $policies;

    private readonly Transport $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
        $this->assistants = new AssistantsV1AssistantsResource($transport);
        $this->tools = new AssistantsV1ToolsResource($transport);
        $this->knowledge = new AssistantsV1KnowledgeResource($transport);
        $this->sessions = new AssistantsV1SessionsResource($transport);
        $this->policies = new AssistantsV1PoliciesResource($transport);
    }

    /** Per-Assistant scope: tools / knowledge / feedbacks / messages. */
    public function assistants(string $assistantId): AssistantsV1AssistantScopeResource
    {
        return new AssistantsV1AssistantScopeResource($this->transport, $assistantId);
    }

    /** Per-Knowledge scope: status / chunks. */
    public function knowledge(string $knowledgeId): AssistantsV1KnowledgeScopeResource
    {
        return new AssistantsV1KnowledgeScopeResource($this->transport, $knowledgeId);
    }

    /** Per-Session scope: messages. */
    public function sessions(string $sessionId): AssistantsV1SessionScopeResource
    {
        return new AssistantsV1SessionScopeResource($this->transport, $sessionId);
    }
}
