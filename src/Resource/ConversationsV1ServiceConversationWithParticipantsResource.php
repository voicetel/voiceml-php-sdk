<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversationWithParticipants;
use VoiceML\Model\CreateConversationsV1ServiceConversationWithParticipantsRequest;
use VoiceML\Transport;

/**
 * Create-only convenience `/v1/Services/{ChatServiceSid}/ConversationWithParticipants`.
 * Creates a service-scoped Conversation together with its initial Participants.
 */
final class ConversationsV1ServiceConversationWithParticipantsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationWithParticipantsRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationWithParticipantsRequest $body = []): ConversationsV1ServiceConversationWithParticipants
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationWithParticipantsRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            "/v1/Services/{$this->chatServiceSid}/ConversationWithParticipants",
            null,
            $form,
        );
        return ConversationsV1ServiceConversationWithParticipants::fromArray($raw);
    }
}
