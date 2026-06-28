<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ParticipantConversationList;
use VoiceML\Transport;

/** `/v1/ParticipantConversations` — read-only cross-conversation participant view. */
final class ConversationsV1ParticipantConversationsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ParticipantConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/ParticipantConversations', $query);
        return ConversationsV1ParticipantConversationList::fromArray($raw);
    }
}
