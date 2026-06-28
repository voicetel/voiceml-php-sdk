<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1UserConversation;
use VoiceML\Model\ConversationsV1UserConversationList;
use VoiceML\Model\UpdateConversationsV1UserConversationRequest;
use VoiceML\Transport;

/**
 * `/v1/Users/{Sid}/Conversations`. Bound to a parent User; produced via
 * {@see ConversationsV1UsersResource::conversations()}.
 */
final class ConversationsV1UserConversationsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $userSid,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1UserConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Users/{$this->userSid}/Conversations", $query);
        return ConversationsV1UserConversationList::fromArray($raw);
    }

    public function fetch(string $conversationSid): ConversationsV1UserConversation
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Users/{$this->userSid}/Conversations/{$conversationSid}");
        return ConversationsV1UserConversation::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1UserConversationRequest $body */
    public function update(string $conversationSid, array|UpdateConversationsV1UserConversationRequest $body = []): ConversationsV1UserConversation
    {
        $form = $body instanceof UpdateConversationsV1UserConversationRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Users/{$this->userSid}/Conversations/{$conversationSid}", null, $form);
        return ConversationsV1UserConversation::fromArray($raw);
    }

    public function delete(string $conversationSid): void
    {
        $this->transport->request('DELETE', "/v1/Users/{$this->userSid}/Conversations/{$conversationSid}");
    }
}
