<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceWebhookConfiguration;
use VoiceML\Model\UpdateConversationsV1ServiceWebhookConfigurationRequest;
use VoiceML\Transport;

/** `/v1/Services/{ChatServiceSid}/Configuration/Webhooks` — per-service webhook config singleton. */
final class ConversationsV1ServiceWebhookConfigurationResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
    ) {
        parent::__construct($transport);
    }

    public function fetch(): ConversationsV1ServiceWebhookConfiguration
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services', $this->chatServiceSid, 'Configuration', 'Webhooks'));
        return ConversationsV1ServiceWebhookConfiguration::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceWebhookConfigurationRequest $body */
    public function update(array|UpdateConversationsV1ServiceWebhookConfigurationRequest $body = []): ConversationsV1ServiceWebhookConfiguration
    {
        $form = $body instanceof UpdateConversationsV1ServiceWebhookConfigurationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Services', $this->chatServiceSid, 'Configuration', 'Webhooks'), null, $form);
        return ConversationsV1ServiceWebhookConfiguration::fromArray($raw);
    }
}
