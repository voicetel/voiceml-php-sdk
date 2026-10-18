<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateMessagingServiceRequest;
use VoiceML\Model\MessagingService;
use VoiceML\Model\MessagingServiceList;
use VoiceML\Model\UpdateMessagingServiceRequest;

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
final class MessagingV1ServicesResource extends Resource
{
    /** @param array<string,mixed>|CreateMessagingServiceRequest $body */
    public function create(array|CreateMessagingServiceRequest $body): MessagingService
    {
        $form = $body instanceof CreateMessagingServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Services'), null, $form);
        return MessagingService::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): MessagingServiceList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services'), $query);
        return MessagingServiceList::fromArray($raw);
    }

    public function fetch(string $sid): MessagingService
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services', $sid));
        return MessagingService::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateMessagingServiceRequest $body */
    public function update(string $sid, array|UpdateMessagingServiceRequest $body = []): MessagingService
    {
        $form = $body instanceof UpdateMessagingServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Services', $sid), null, $form);
        return MessagingService::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Services', $sid));
    }

    /**
     * A service's sender pool (`/v1/Services/{sid}/PhoneNumbers`).
     */
    public function phoneNumbers(string $chatServiceSid): MessagingV1ServicePhoneNumbersResource
    {
        return new MessagingV1ServicePhoneNumbersResource($this->transport, $chatServiceSid);
    }
}
