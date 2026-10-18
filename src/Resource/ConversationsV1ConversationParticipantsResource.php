<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConversationParticipant;
use VoiceML\Model\ConversationsV1ConversationParticipantList;
use VoiceML\Model\CreateConversationsV1ConversationParticipantRequest;
use VoiceML\Model\UpdateConversationsV1ConversationParticipantRequest;
use VoiceML\Transport;

/**
 * `/v1/Conversations/{ConversationSid}/Participants`. Bound to a parent
 * Conversation; produced via {@see ConversationsV1ConversationsResource::participants()}.
 */
final class ConversationsV1ConversationParticipantsResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $conversationSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed>|CreateConversationsV1ConversationParticipantRequest $body */
    public function create(array|CreateConversationsV1ConversationParticipantRequest $body = []): ConversationsV1ConversationParticipant
    {
        $form = $body instanceof CreateConversationsV1ConversationParticipantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Conversations', $this->conversationSid, 'Participants'), null, $form);
        return ConversationsV1ConversationParticipant::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConversationParticipantList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Conversations', $this->conversationSid, 'Participants'), $query);
        return ConversationsV1ConversationParticipantList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ConversationParticipant
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Conversations', $this->conversationSid, 'Participants', $sid));
        return ConversationsV1ConversationParticipant::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConversationParticipantRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ConversationParticipantRequest $body = []): ConversationsV1ConversationParticipant
    {
        $form = $body instanceof UpdateConversationsV1ConversationParticipantRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Conversations', $this->conversationSid, 'Participants', $sid), null, $form);
        return ConversationsV1ConversationParticipant::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Conversations', $this->conversationSid, 'Participants', $sid));
    }
}
