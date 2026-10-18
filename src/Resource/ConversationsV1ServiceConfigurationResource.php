<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConfiguration;
use VoiceML\Model\UpdateConversationsV1ServiceConfigurationRequest;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/Configuration` — per-service Conversations
 * configuration singleton. Exposes nested `notifications` and `webhooks`
 * sub-resources for per-service push and webhook config.
 */
final class ConversationsV1ServiceConfigurationResource extends Resource
{
    public readonly ConversationsV1ServiceNotificationResource $notifications;
    public readonly ConversationsV1ServiceWebhookConfigurationResource $webhooks;

    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
    ) {
        parent::__construct($transport);
        $this->notifications = new ConversationsV1ServiceNotificationResource($transport, $chatServiceSid);
        $this->webhooks = new ConversationsV1ServiceWebhookConfigurationResource($transport, $chatServiceSid);
    }

    public function fetch(): ConversationsV1ServiceConfiguration
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services', $this->chatServiceSid, 'Configuration'));
        return ConversationsV1ServiceConfiguration::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceConfigurationRequest $body */
    public function update(array|UpdateConversationsV1ServiceConfigurationRequest $body = []): ConversationsV1ServiceConfiguration
    {
        $form = $body instanceof UpdateConversationsV1ServiceConfigurationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Services', $this->chatServiceSid, 'Configuration'), null, $form);
        return ConversationsV1ServiceConfiguration::fromArray($raw);
    }
}
