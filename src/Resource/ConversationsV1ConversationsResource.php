<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Conversation;
use VoiceML\Model\ConversationsV1ConversationList;
use VoiceML\Model\CreateConversationsV1ConversationRequest;
use VoiceML\Model\UpdateConversationsV1ConversationRequest;
use VoiceML\Transport;

/** `/v1/Conversations` — Twilio Conversations v1 top-level resource. */
final class ConversationsV1ConversationsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1ConversationRequest $body */
    public function create(array|CreateConversationsV1ConversationRequest $body = []): ConversationsV1Conversation
    {
        $form = $body instanceof CreateConversationsV1ConversationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Conversations', null, $form);
        return ConversationsV1Conversation::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Conversations', $query);
        return ConversationsV1ConversationList::fromArray($raw);
    }

    public function fetch(string $conversationSid): ConversationsV1Conversation
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Conversations/{$conversationSid}");
        return ConversationsV1Conversation::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConversationRequest $body */
    public function update(string $conversationSid, array|UpdateConversationsV1ConversationRequest $body = []): ConversationsV1Conversation
    {
        $form = $body instanceof UpdateConversationsV1ConversationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Conversations/{$conversationSid}", null, $form);
        return ConversationsV1Conversation::fromArray($raw);
    }

    public function delete(string $conversationSid): void
    {
        $this->transport->request('DELETE', "/v1/Conversations/{$conversationSid}");
    }

    /** Sub-collection: per-conversation messages. */
    public function messages(string $conversationSid): ConversationsV1ConversationMessagesResource
    {
        return new ConversationsV1ConversationMessagesResource($this->transport, $conversationSid);
    }

    /** Sub-collection: per-conversation participants. */
    public function participants(string $conversationSid): ConversationsV1ConversationParticipantsResource
    {
        return new ConversationsV1ConversationParticipantsResource($this->transport, $conversationSid);
    }

    /** Sub-collection: per-conversation scoped webhooks. */
    public function webhooks(string $conversationSid): ConversationsV1ConversationWebhooksResource
    {
        return new ConversationsV1ConversationWebhooksResource($this->transport, $conversationSid);
    }
}
