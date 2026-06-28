<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Conversations/{ConversationSid}/Participants/{ParticipantSid}`. */
final class UpdateConversationsV1ConversationParticipantRequest
{
    public function __construct(
        public readonly ?string $identity = null,
        public readonly ?string $attributes = null,
        public readonly ?string $roleSid = null,
        public readonly ?int $lastReadMessageIndex = null,
        public readonly ?string $lastReadTimestamp = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->identity !== null) $out['Identity'] = $this->identity;
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        if ($this->roleSid !== null) $out['RoleSid'] = $this->roleSid;
        if ($this->lastReadMessageIndex !== null) $out['LastReadMessageIndex'] = $this->lastReadMessageIndex;
        if ($this->lastReadTimestamp !== null) $out['LastReadTimestamp'] = $this->lastReadTimestamp;
        return $out;
    }
}
