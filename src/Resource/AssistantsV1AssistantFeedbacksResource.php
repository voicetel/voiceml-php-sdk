<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1Feedback;
use VoiceML\Model\AssistantsV1FeedbackList;
use VoiceML\Model\CreateAssistantsV1FeedbackRequest;
use VoiceML\Transport;

/**
 * `/v1/Assistants/{id}/Feedbacks` — per-assistant Feedback list + create.
 * Bound to a parent AssistantId.
 */
final class AssistantsV1AssistantFeedbacksResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $assistantId,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1FeedbackList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Assistants/{$this->assistantId}/Feedbacks", $query);
        return AssistantsV1FeedbackList::fromArray($raw);
    }

    /** @param array<string,mixed>|CreateAssistantsV1FeedbackRequest $body */
    public function create(array|CreateAssistantsV1FeedbackRequest $body): AssistantsV1Feedback
    {
        $json = $body instanceof CreateAssistantsV1FeedbackRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Assistants/{$this->assistantId}/Feedbacks", null, null, $json);
        return AssistantsV1Feedback::fromArray($raw);
    }
}
