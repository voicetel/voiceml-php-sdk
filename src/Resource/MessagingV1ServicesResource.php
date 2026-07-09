<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateMessagingServiceRequest;
use VoiceML\Model\MessagingService;
use VoiceML\Model\MessagingServiceList;
use VoiceML\Model\UpdateMessagingServiceRequest;
use VoiceML\Transport;

/**
 * `/v1/Services` — Twilio Messaging v1 Services (`MG…`).
 *
 * The whole group is routed at the messaging host (`messaging.voicetel.com`) by
 * the client, which is what disambiguates a Messaging Service from a
 * Conversation Service (`IS…`) — they share the `/v1/Services` path shape.
 *
 * `create` / `list` / `fetch` / `delete` reuse the shared path; `update`
 * (`POST /v1/Services/{sid}`) is unique to Messaging Service.
 */
final class MessagingV1ServicesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateMessagingServiceRequest $body */
    public function create(array|CreateMessagingServiceRequest $body): MessagingService
    {
        $form = $body instanceof CreateMessagingServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Services', null, $form);
        return MessagingService::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): MessagingServiceList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Services', $query);
        return MessagingServiceList::fromArray($raw);
    }

    public function fetch(string $sid): MessagingService
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$sid}");
        return MessagingService::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateMessagingServiceRequest $body */
    public function update(string $sid, array|UpdateMessagingServiceRequest $body = []): MessagingService
    {
        $form = $body instanceof UpdateMessagingServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$sid}", null, $form);
        return MessagingService::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$sid}");
    }
}
