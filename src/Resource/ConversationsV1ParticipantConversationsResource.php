<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ParticipantConversationList;

/** `/v1/ParticipantConversations` — read-only cross-conversation participant view. */
final class ConversationsV1ParticipantConversationsResource extends Resource
{

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ParticipantConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('ParticipantConversations'), $query);
        return ConversationsV1ParticipantConversationList::fromArray($raw);
    }
}
