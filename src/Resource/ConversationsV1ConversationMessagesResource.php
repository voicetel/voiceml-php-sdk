<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConversationMessage;
use VoiceML\Model\ConversationsV1ConversationMessageList;
use VoiceML\Model\CreateConversationsV1ConversationMessageRequest;
use VoiceML\Model\UpdateConversationsV1ConversationMessageRequest;
use VoiceML\Transport;

/**
 * `/v1/Conversations/{ConversationSid}/Messages`. Bound to a parent
 * Conversation; produced via {@see ConversationsV1ConversationsResource::messages()}.
 */
final class ConversationsV1ConversationMessagesResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $conversationSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ConversationMessageRequest $body */
    public function create(array|CreateConversationsV1ConversationMessageRequest $body = []): ConversationsV1ConversationMessage
    {
        $form = $body instanceof CreateConversationsV1ConversationMessageRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Conversations/{$this->conversationSid}/Messages", null, $form);
        return ConversationsV1ConversationMessage::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConversationMessageList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Conversations/{$this->conversationSid}/Messages", $query);
        return ConversationsV1ConversationMessageList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ConversationMessage
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Conversations/{$this->conversationSid}/Messages/{$sid}");
        return ConversationsV1ConversationMessage::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConversationMessageRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ConversationMessageRequest $body = []): ConversationsV1ConversationMessage
    {
        $form = $body instanceof UpdateConversationsV1ConversationMessageRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Conversations/{$this->conversationSid}/Messages/{$sid}", null, $form);
        return ConversationsV1ConversationMessage::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Conversations/{$this->conversationSid}/Messages/{$sid}");
    }

    /** Sub-collection: per-channel delivery receipts. */
    public function receipts(string $messageSid): ConversationsV1ConversationMessageReceiptsResource
    {
        return new ConversationsV1ConversationMessageReceiptsResource(
            $this->transport,
            $this->conversationSid,
            $messageSid,
        );
    }
}
