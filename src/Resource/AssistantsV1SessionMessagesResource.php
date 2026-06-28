<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1MessageList;
use VoiceML\Transport;

/** `/v1/Sessions/{id}/Messages` — read-only paginated session-message list. */
final class AssistantsV1SessionMessagesResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $sessionId,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1MessageList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Sessions/{$this->sessionId}/Messages", $query);
        return AssistantsV1MessageList::fromArray($raw);
    }
}
