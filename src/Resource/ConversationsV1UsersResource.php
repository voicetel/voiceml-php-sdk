<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1User;
use VoiceML\Model\ConversationsV1UserList;
use VoiceML\Model\CreateConversationsV1UserRequest;
use VoiceML\Model\UpdateConversationsV1UserRequest;
use VoiceML\Transport;

/** `/v1/Users` — Twilio Conversations v1 Users. */
final class ConversationsV1UsersResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1UserRequest $body */
    public function create(array|CreateConversationsV1UserRequest $body): ConversationsV1User
    {
        $form = $body instanceof CreateConversationsV1UserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Users', null, $form);
        return ConversationsV1User::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1UserList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Users', $query);
        return ConversationsV1UserList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1User
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Users/{$sid}");
        return ConversationsV1User::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1UserRequest $body */
    public function update(string $sid, array|UpdateConversationsV1UserRequest $body = []): ConversationsV1User
    {
        $form = $body instanceof UpdateConversationsV1UserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Users/{$sid}", null, $form);
        return ConversationsV1User::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Users/{$sid}");
    }

    /** Sub-collection: a user's conversations. */
    public function conversations(string $userSid): ConversationsV1UserConversationsResource
    {
        return new ConversationsV1UserConversationsResource($this->transport, $userSid);
    }
}
