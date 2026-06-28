<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceWebhookConfiguration;
use VoiceML\Model\UpdateConversationsV1ServiceWebhookConfigurationRequest;
use VoiceML\Transport;

/** `/v1/Services/{ChatServiceSid}/Configuration/Webhooks` — per-service webhook config singleton. */
final class ConversationsV1ServiceWebhookConfigurationResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    public function fetch(): ConversationsV1ServiceWebhookConfiguration
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Configuration/Webhooks");
        return ConversationsV1ServiceWebhookConfiguration::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceWebhookConfigurationRequest $body */
    public function update(array|UpdateConversationsV1ServiceWebhookConfigurationRequest $body = []): ConversationsV1ServiceWebhookConfiguration
    {
        $form = $body instanceof UpdateConversationsV1ServiceWebhookConfigurationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Configuration/Webhooks", null, $form);
        return ConversationsV1ServiceWebhookConfiguration::fromArray($raw);
    }
}
