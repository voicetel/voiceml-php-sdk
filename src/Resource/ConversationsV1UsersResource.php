<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1User;
use VoiceML\Model\ConversationsV1UserList;
use VoiceML\Model\CreateConversationsV1UserRequest;
use VoiceML\Model\UpdateConversationsV1UserRequest;

/** `/v1/Users` — Twilio Conversations v1 Users. */
final class ConversationsV1UsersResource extends Resource
{

    /** @param array<string,mixed>|CreateConversationsV1UserRequest $body */
    public function create(array|CreateConversationsV1UserRequest $body): ConversationsV1User
    {
        $form = $body instanceof CreateConversationsV1UserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Users'), null, $form);
        return ConversationsV1User::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1UserList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Users'), $query);
        return ConversationsV1UserList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1User
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Users', $sid));
        return ConversationsV1User::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1UserRequest $body */
    public function update(string $sid, array|UpdateConversationsV1UserRequest $body = []): ConversationsV1User
    {
        $form = $body instanceof UpdateConversationsV1UserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Users', $sid), null, $form);
        return ConversationsV1User::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Users', $sid));
    }

    /** Sub-collection: a user's conversations. */
    public function conversations(string $userSid): ConversationsV1UserConversationsResource
    {
        return new ConversationsV1UserConversationsResource($this->transport, $userSid);
    }
}
