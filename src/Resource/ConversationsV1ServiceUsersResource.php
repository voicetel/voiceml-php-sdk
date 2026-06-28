<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceUser;
use VoiceML\Model\ConversationsV1ServiceUserList;
use VoiceML\Model\CreateConversationsV1ServiceUserRequest;
use VoiceML\Model\UpdateConversationsV1ServiceUserRequest;
use VoiceML\Transport;

/** `/v1/Services/{ChatServiceSid}/Users`. */
final class ConversationsV1ServiceUsersResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceUserRequest $body */
    public function create(array|CreateConversationsV1ServiceUserRequest $body): ConversationsV1ServiceUser
    {
        $form = $body instanceof CreateConversationsV1ServiceUserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Users", null, $form);
        return ConversationsV1ServiceUser::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceUserList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Users", $query);
        return ConversationsV1ServiceUserList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceUser
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Users/{$sid}");
        return ConversationsV1ServiceUser::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceUserRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ServiceUserRequest $body = []): ConversationsV1ServiceUser
    {
        $form = $body instanceof UpdateConversationsV1ServiceUserRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Users/{$sid}", null, $form);
        return ConversationsV1ServiceUser::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Users/{$sid}");
    }

    /** Sub-collection: a user's conversations within this service. */
    public function conversations(string $userSid): ConversationsV1ServiceUserConversationsResource
    {
        return new ConversationsV1ServiceUserConversationsResource($this->transport, $this->chatServiceSid, $userSid);
    }
}
