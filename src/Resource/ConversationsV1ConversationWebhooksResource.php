<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConversationScopedWebhook;
use VoiceML\Model\ConversationsV1ConversationScopedWebhookList;
use VoiceML\Model\CreateConversationsV1ConversationScopedWebhookRequest;
use VoiceML\Model\UpdateConversationsV1ConversationScopedWebhookRequest;
use VoiceML\Transport;

/**
 * `/v1/Conversations/{ConversationSid}/Webhooks`. Bound to a parent
 * Conversation; produced via {@see ConversationsV1ConversationsResource::webhooks()}.
 */
final class ConversationsV1ConversationWebhooksResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $conversationSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed>|CreateConversationsV1ConversationScopedWebhookRequest $body */
    public function create(array|CreateConversationsV1ConversationScopedWebhookRequest $body): ConversationsV1ConversationScopedWebhook
    {
        $form = $body instanceof CreateConversationsV1ConversationScopedWebhookRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Conversations', $this->conversationSid, 'Webhooks'), null, $form);
        return ConversationsV1ConversationScopedWebhook::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConversationScopedWebhookList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Conversations', $this->conversationSid, 'Webhooks'), $query);
        return ConversationsV1ConversationScopedWebhookList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ConversationScopedWebhook
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Conversations', $this->conversationSid, 'Webhooks', $sid));
        return ConversationsV1ConversationScopedWebhook::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConversationScopedWebhookRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ConversationScopedWebhookRequest $body = []): ConversationsV1ConversationScopedWebhook
    {
        $form = $body instanceof UpdateConversationsV1ConversationScopedWebhookRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Conversations', $this->conversationSid, 'Webhooks', $sid), null, $form);
        return ConversationsV1ConversationScopedWebhook::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Conversations', $this->conversationSid, 'Webhooks', $sid));
    }
}
