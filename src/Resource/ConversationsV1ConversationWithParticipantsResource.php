<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConversationWithParticipants;
use VoiceML\Model\CreateConversationsV1ConversationWithParticipantsRequest;
use VoiceML\Transport;

/** `/v1/ConversationWithParticipants` — create-only convenience endpoint. */
final class ConversationsV1ConversationWithParticipantsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1ConversationWithParticipantsRequest $body */
    public function create(array|CreateConversationsV1ConversationWithParticipantsRequest $body = []): ConversationsV1ConversationWithParticipants
    {
        $form = $body instanceof CreateConversationsV1ConversationWithParticipantsRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/ConversationWithParticipants', null, $form);
        return ConversationsV1ConversationWithParticipants::fromArray($raw);
    }
}
