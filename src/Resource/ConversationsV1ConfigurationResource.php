<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Configuration;
use VoiceML\Model\UpdateConversationsV1ConfigurationRequest;
use VoiceML\Transport;

/**
 * `/v1/Configuration` — account-wide Conversations configuration. Exposes
 * the singleton-style Configuration as well as the nested
 * `webhooks` and `addresses` sub-resources.
 */
final class ConversationsV1ConfigurationResource
{
    public readonly ConversationsV1ConfigurationWebhooksResource $webhooks;
    public readonly ConversationsV1ConfigAddressesResource $addresses;

    public function __construct(private readonly Transport $transport)
    {
        $this->webhooks = new ConversationsV1ConfigurationWebhooksResource($transport);
        $this->addresses = new ConversationsV1ConfigAddressesResource($transport);
    }

    public function fetch(): ConversationsV1Configuration
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Configuration');
        return ConversationsV1Configuration::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConfigurationRequest $body */
    public function update(array|UpdateConversationsV1ConfigurationRequest $body = []): ConversationsV1Configuration
    {
        $form = $body instanceof UpdateConversationsV1ConfigurationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Configuration', null, $form);
        return ConversationsV1Configuration::fromArray($raw);
    }
}
