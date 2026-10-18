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
final class ConversationsV1ServiceConversationWithParticipantsResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationWithParticipantsRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationWithParticipantsRequest $body = []): ConversationsV1ServiceConversationWithParticipants
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationWithParticipantsRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->v1Path('Services', $this->chatServiceSid, 'ConversationWithParticipants'),
            null,
            $form,
        );
        return ConversationsV1ServiceConversationWithParticipants::fromArray($raw);
    }
}
