<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversationMessage;
use VoiceML\Model\ConversationsV1ServiceConversationMessageList;
use VoiceML\Model\CreateConversationsV1ServiceConversationMessageRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationMessageRequest;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Messages`.
 * Produced via {@see ConversationsV1ServiceConversationsResource::messages()}.
 */
final class ConversationsV1ServiceConversationMessagesResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
        private readonly string $conversationSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationMessageRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationMessageRequest $body = []): ConversationsV1ServiceConversationMessage
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationMessageRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages", null, $form);
        return ConversationsV1ServiceConversationMessage::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceConversationMessageList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages", $query);
        return ConversationsV1ServiceConversationMessageList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceConversationMessage
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages/{$sid}");
        return ConversationsV1ServiceConversationMessage::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceConversationMessageRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ServiceConversationMessageRequest $body = []): ConversationsV1ServiceConversationMessage
    {
        $form = $body instanceof UpdateConversationsV1ServiceConversationMessageRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages/{$sid}", null, $form);
        return ConversationsV1ServiceConversationMessage::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages/{$sid}");
    }

    /** Sub-collection: per-channel delivery receipts. */
    public function receipts(string $messageSid): ConversationsV1ServiceConversationMessageReceiptsResource
    {
        return new ConversationsV1ServiceConversationMessageReceiptsResource(
            $this->transport,
            $this->chatServiceSid,
            $this->conversationSid,
            $messageSid,
        );
    }
}
