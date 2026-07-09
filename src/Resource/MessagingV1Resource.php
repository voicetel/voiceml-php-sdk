<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->messagingV1` — top-level holder for the Twilio Messaging v1
 * (messaging.twilio.com/v1) family. The whole group rides the messaging host
 * (`messaging.voicetel.com`). {@see \VoiceML\Hosts}.
 *
 *  - `services` — `/v1/Services` Messaging Service CRUD (`MG…`).
 */
final class MessagingV1Resource
{
    public readonly MessagingV1ServicesResource $services;

    public function __construct(Transport $transport)
    {
        $this->services = new MessagingV1ServicesResource($transport);
    }
}
