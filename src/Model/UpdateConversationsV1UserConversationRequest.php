<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Users/{Sid}/Conversations/{ConversationSid}`. */
final class UpdateConversationsV1UserConversationRequest
{
    public function __construct(
        public readonly ?string $notificationLevel = null,
        public readonly ?int $lastReadMessageIndex = null,
        public readonly ?string $lastReadTimestamp = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->notificationLevel !== null) $out['NotificationLevel'] = $this->notificationLevel;
        if ($this->lastReadMessageIndex !== null) $out['LastReadMessageIndex'] = $this->lastReadMessageIndex;
        if ($this->lastReadTimestamp !== null) $out['LastReadTimestamp'] = $this->lastReadTimestamp;
        return $out;
    }
}
