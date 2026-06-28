<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Role;
use VoiceML\Model\ConversationsV1RoleList;
use VoiceML\Model\CreateConversationsV1RoleRequest;
use VoiceML\Model\UpdateConversationsV1RoleRequest;
use VoiceML\Transport;

/** `/v1/Roles` — Twilio Conversations v1 Roles. */
final class ConversationsV1RolesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1RoleRequest $body */
    public function create(array|CreateConversationsV1RoleRequest $body): ConversationsV1Role
    {
        $form = $body instanceof CreateConversationsV1RoleRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Roles', null, $form);
        return ConversationsV1Role::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1RoleList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Roles', $query);
        return ConversationsV1RoleList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1Role
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Roles/{$sid}");
        return ConversationsV1Role::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1RoleRequest $body */
    public function update(string $sid, array|UpdateConversationsV1RoleRequest $body): ConversationsV1Role
    {
        $form = $body instanceof UpdateConversationsV1RoleRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Roles/{$sid}", null, $form);
        return ConversationsV1Role::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Roles/{$sid}");
    }
}
