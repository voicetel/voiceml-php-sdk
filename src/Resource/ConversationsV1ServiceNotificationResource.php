<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceNotification;
use VoiceML\Model\UpdateConversationsV1ServiceNotificationRequest;
use VoiceML\Transport;

/** `/v1/Services/{ChatServiceSid}/Configuration/Notifications` — per-service push notification config singleton. */
final class ConversationsV1ServiceNotificationResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    public function fetch(): ConversationsV1ServiceNotification
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Configuration/Notifications");
        return ConversationsV1ServiceNotification::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceNotificationRequest $body */
    public function update(array|UpdateConversationsV1ServiceNotificationRequest $body = []): ConversationsV1ServiceNotification
    {
        $form = $body instanceof UpdateConversationsV1ServiceNotificationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Configuration/Notifications", null, $form);
        return ConversationsV1ServiceNotification::fromArray($raw);
    }
}
