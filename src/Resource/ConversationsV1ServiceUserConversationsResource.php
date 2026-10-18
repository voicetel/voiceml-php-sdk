<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceUserConversationList;
use VoiceML\Transport;

/**
 * Read-only `/v1/Services/{ChatServiceSid}/Users/{UserSid}/Conversations`.
 * Produced via {@see ConversationsV1ServiceUsersResource::conversations()}.
 */
final class ConversationsV1ServiceUserConversationsResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
        private readonly string $userSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceUserConversationList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v1Path('Services', $this->chatServiceSid, 'Users', $this->userSid, 'Conversations'),
            $query,
        );
        return ConversationsV1ServiceUserConversationList::fromArray($raw);
    }
}
