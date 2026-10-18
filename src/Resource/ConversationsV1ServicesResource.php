<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Service;
use VoiceML\Model\ConversationsV1ServiceList;
use VoiceML\Model\CreateConversationsV1ServiceRequest;

/** `/v1/Services` — Twilio Conversations v1 Services. */
final class ConversationsV1ServicesResource extends Resource
{

    /** @param array<string,mixed>|CreateConversationsV1ServiceRequest $body */
    public function create(array|CreateConversationsV1ServiceRequest $body): ConversationsV1Service
    {
        $form = $body instanceof CreateConversationsV1ServiceRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Services'), null, $form);
        return ConversationsV1Service::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services'), $query);
        return ConversationsV1ServiceList::fromArray($raw);
    }

    public function fetch(string $chatServiceSid): ConversationsV1Service
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Services', $chatServiceSid));
        return ConversationsV1Service::fromArray($raw);
    }

    public function delete(string $chatServiceSid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Services', $chatServiceSid));
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
