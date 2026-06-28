<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `/v1/Sessions/{id}/…` — session-scoped sub-resource tree. Bound to a
 * parent SessionId; produced via {@see AssistantsV1Resource::sessions()}.
 *
 *  - `messages` — `/v1/Sessions/{id}/Messages` (read-only paginated)
 */
final class AssistantsV1SessionScopeResource
{
    public readonly AssistantsV1SessionMessagesResource $messages;

    public function __construct(Transport $transport, string $sessionId)
    {
        $this->messages = new AssistantsV1SessionMessagesResource($transport, $sessionId);
    }
}
