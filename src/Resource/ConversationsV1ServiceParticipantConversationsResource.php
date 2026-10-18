<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceParticipantConversationList;
use VoiceML\Transport;

/** Read-only `/v1/Services/{ChatServiceSid}/ParticipantConversations`. */
final class ConversationsV1ServiceParticipantConversationsResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceParticipantConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v1Path('Services', $this->chatServiceSid, 'ParticipantConversations'),
            $query,
        );
        return ConversationsV1ServiceParticipantConversationList::fromArray($raw);
    }
}
