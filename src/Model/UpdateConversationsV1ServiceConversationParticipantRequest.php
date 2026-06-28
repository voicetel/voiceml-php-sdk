<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Participants/{ParticipantSid}`. */
final class UpdateConversationsV1ServiceConversationParticipantRequest
{
    public function __construct(
        public readonly ?string $attributes = null,
        public readonly ?string $roleSid = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        if ($this->roleSid !== null) $out['RoleSid'] = $this->roleSid;
        return $out;
    }
}
