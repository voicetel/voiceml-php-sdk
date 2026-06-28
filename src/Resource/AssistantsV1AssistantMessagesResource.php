<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1SendMessageResponse;
use VoiceML\Model\SendAssistantsV1AssistantMessageRequest;
use VoiceML\Transport;

/**
 * `/v1/Assistants/{id}/Messages` — send-message endpoint. POST creates (and
 * may continue) a Session and returns the assistant reply.
 */
final class AssistantsV1AssistantMessagesResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $assistantId,
    ) {
    }

    /** @param array<string,mixed>|SendAssistantsV1AssistantMessageRequest $body */
    public function create(array|SendAssistantsV1AssistantMessageRequest $body): AssistantsV1SendMessageResponse
    {
        $json = $body instanceof SendAssistantsV1AssistantMessageRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Assistants/{$this->assistantId}/Messages", null, null, $json);
        return AssistantsV1SendMessageResponse::fromArray($raw);
    }
}
