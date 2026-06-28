<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversationScopedWebhook;
use VoiceML\Model\ConversationsV1ServiceConversationScopedWebhookList;
use VoiceML\Model\CreateConversationsV1ServiceConversationScopedWebhookRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationScopedWebhookRequest;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Webhooks`.
 * Produced via {@see ConversationsV1ServiceConversationsResource::webhooks()}.
 */
final class ConversationsV1ServiceConversationWebhooksResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
        private readonly string $conversationSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceConversationScopedWebhookRequest $body */
    public function create(array|CreateConversationsV1ServiceConversationScopedWebhookRequest $body): ConversationsV1ServiceConversationScopedWebhook
    {
        $form = $body instanceof CreateConversationsV1ServiceConversationScopedWebhookRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Webhooks", null, $form);
        return ConversationsV1ServiceConversationScopedWebhook::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceConversationScopedWebhookList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Webhooks", $query);
        return ConversationsV1ServiceConversationScopedWebhookList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceConversationScopedWebhook
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Webhooks/{$sid}");
        return ConversationsV1ServiceConversationScopedWebhook::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceConversationScopedWebhookRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ServiceConversationScopedWebhookRequest $body = []): ConversationsV1ServiceConversationScopedWebhook
    {
        $form = $body instanceof UpdateConversationsV1ServiceConversationScopedWebhookRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Webhooks/{$sid}", null, $form);
        return ConversationsV1ServiceConversationScopedWebhook::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Webhooks/{$sid}");
    }
}
