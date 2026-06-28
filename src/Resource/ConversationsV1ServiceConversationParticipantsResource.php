<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversationParticipant;
use VoiceML\Model\ConversationsV1ServiceConversationParticipantList;
use VoiceML\Model\CreateConversationsV1ServiceConversationParticipantRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationParticipantRequest;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Participants`.
 * Produced via {@see ConversationsV1ServiceConversationsResource::participants()}.
 */
final class ConversationsV1ServiceConversationParticipantsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
        private readonly string $conversationSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationParticipantRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationParticipantRequest $body = []): ConversationsV1ServiceConversationParticipant
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationParticipantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Participants", null, $form);
        return ConversationsV1ServiceConversationParticipant::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceConversationParticipantList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Participants", $query);
        return ConversationsV1ServiceConversationParticipantList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceConversationParticipant
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Participants/{$sid}");
        return ConversationsV1ServiceConversationParticipant::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceConversationParticipantRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ServiceConversationParticipantRequest $body = []): ConversationsV1ServiceConversationParticipant
    {
        $form = $body instanceof UpdateConversationsV1ServiceConversationParticipantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Participants/{$sid}", null, $form);
        return ConversationsV1ServiceConversationParticipant::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Participants/{$sid}");
    }
}
