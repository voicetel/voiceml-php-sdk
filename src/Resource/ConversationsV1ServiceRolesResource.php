<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceRole;
use VoiceML\Model\ConversationsV1ServiceRoleList;
use VoiceML\Model\CreateConversationsV1ServiceRoleRequest;
use VoiceML\Model\UpdateConversationsV1ServiceRoleRequest;
use VoiceML\Transport;

/** `/v1/Services/{ChatServiceSid}/Roles`. */
final class ConversationsV1ServiceRolesResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    /** @param array<string,mixed>|CreateConversationsV1ServiceRoleRequest $body */
    public function create(array|CreateConversationsV1ServiceRoleRequest $body): ConversationsV1ServiceRole
    {
        $form = $body instanceof CreateConversationsV1ServiceRoleRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Roles", null, $form);
        return ConversationsV1ServiceRole::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceRoleList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Roles", $query);
        return ConversationsV1ServiceRoleList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceRole
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Roles/{$sid}");
        return ConversationsV1ServiceRole::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ServiceRoleRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ServiceRoleRequest $body): ConversationsV1ServiceRole
    {
        $form = $body instanceof UpdateConversationsV1ServiceRoleRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Services/{$this->chatServiceSid}/Roles/{$sid}", null, $form);
        return ConversationsV1ServiceRole::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Roles/{$sid}");
    }
}
