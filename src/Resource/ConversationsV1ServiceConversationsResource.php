<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversation;
use VoiceML\Model\ConversationsV1ServiceConversationList;
use VoiceML\Model\CreateConversationsV1ServiceConversationRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationRequest;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/Conversations`. Bound to a parent service;
 * produced via {@see ConversationsV1ServiceScopeResource::$conversations}.
 */
final class ConversationsV1ServiceConversationsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationRequest $body = []): ConversationsV1ServiceConversation
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations", null, $form);
        return ConversationsV1ServiceConversation::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations", $query);
        return ConversationsV1ServiceConversationList::fromArray($raw);
    }

    public function fetch(string $conversationSid): ConversationsV1ServiceConversation
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$conversationSid}");
        return ConversationsV1ServiceConversation::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceConversationRequest $body */
    public function update(string $conversationSid, array|UpdateConversationsV1ServiceConversationRequest $body = []): ConversationsV1ServiceConversation
    {
        $form = $body instanceof UpdateConversationsV1ServiceConversationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$conversationSid}", null, $form);
        return ConversationsV1ServiceConversation::fromArray($raw);
    }

    public function delete(string $conversationSid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Conversations/{$conversationSid}");
    }

    /** Sub-collection: per-conversation messages. */
    public function messages(string $conversationSid): ConversationsV1ServiceConversationMessagesResource
    {
        return new ConversationsV1ServiceConversationMessagesResource($this->transport, $this->chatServiceSid, $conversationSid);
    }

    /** Sub-collection: per-conversation participants. */
    public function participants(string $conversationSid): ConversationsV1ServiceConversationParticipantsResource
    {
        return new ConversationsV1ServiceConversationParticipantsResource($this->transport, $this->chatServiceSid, $conversationSid);
    }

    /** Sub-collection: per-conversation scoped webhooks. */
    public function webhooks(string $conversationSid): ConversationsV1ServiceConversationWebhooksResource
    {
        return new ConversationsV1ServiceConversationWebhooksResource($this->transport, $this->chatServiceSid, $conversationSid);
    }
}
