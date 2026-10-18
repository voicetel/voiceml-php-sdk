<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1MessageList;
use VoiceML\Transport;

/** `/v1/Sessions/{id}/Messages` — read-only paginated session-message list. */
final class AssistantsV1SessionMessagesResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $sessionId,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1MessageList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Sessions', $this->sessionId, 'Messages'), $query);
        return AssistantsV1MessageList::fromArray($raw);
    }
}
