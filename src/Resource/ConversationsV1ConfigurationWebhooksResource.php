<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConfigurationWebhook;
use VoiceML\Model\UpdateConversationsV1ConfigurationWebhookRequest;
use VoiceML\Transport;

/** `/v1/Configuration/Webhooks` — account-global Conversation webhook config. */
final class ConversationsV1ConfigurationWebhooksResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    public function fetch(): ConversationsV1ConfigurationWebhook
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Configuration/Webhooks');
        return ConversationsV1ConfigurationWebhook::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConfigurationWebhookRequest $body */
    public function update(array|UpdateConversationsV1ConfigurationWebhookRequest $body = []): ConversationsV1ConfigurationWebhook
    {
        $form = $body instanceof UpdateConversationsV1ConfigurationWebhookRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Configuration/Webhooks', null, $form);
        return ConversationsV1ConfigurationWebhook::fromArray($raw);
    }
}
