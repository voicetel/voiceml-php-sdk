<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Service;
use VoiceML\Model\ConversationsV1ServiceList;
use VoiceML\Model\CreateConversationsV1ServiceRequest;
use VoiceML\Transport;

/** `/v1/Services` — Twilio Conversations v1 Services. */
final class ConversationsV1ServicesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceRequest $body */
    public function create(array|CreateConversationsV1ServiceRequest $body): ConversationsV1Service
    {
        $form = $body instanceof CreateConversationsV1ServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Services', null, $form);
        return ConversationsV1Service::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Services', $query);
        return ConversationsV1ServiceList::fromArray($raw);
    }

    public function fetch(string $chatServiceSid): ConversationsV1Service
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$chatServiceSid}");
        return ConversationsV1Service::fromArray($raw);
    }

    public function delete(string $chatServiceSid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$chatServiceSid}");
    }

    /**
     * Service-scoped Conversations v1 sub-tree. Returns a value object with
     * 14 sub-resources covering all service-scoped Conversations v1 ops under
     * `/v1/Services/{ChatServiceSid}/…`. Equivalent of Twilio's
     * `conversations.v1.services(chatServiceSid).{conversations,roles,…}`.
     */
    public function scope(string $chatServiceSid): ConversationsV1ServiceScopeResource
    {
        return new ConversationsV1ServiceScopeResource($this->transport, $chatServiceSid);
    }
}
