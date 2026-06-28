<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Conversations`. */
final class CreateConversationsV1ConversationRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $uniqueName = null,
        public readonly ?string $messagingServiceSid = null,
        public readonly ?string $attributes = null,
        public readonly ?string $state = null,
        public readonly ?string $timersInactive = null,
        public readonly ?string $timersClosed = null,
        public readonly ?string $bindingsEmailAddress = null,
        public readonly ?string $bindingsEmailName = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->uniqueName !== null) $out['UniqueName'] = $this->uniqueName;
        if ($this->messagingServiceSid !== null) $out['MessagingServiceSid'] = $this->messagingServiceSid;
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        if ($this->state !== null) $out['State'] = $this->state;
        if ($this->timersInactive !== null) $out['Timers.Inactive'] = $this->timersInactive;
        if ($this->timersClosed !== null) $out['Timers.Closed'] = $this->timersClosed;
        if ($this->bindingsEmailAddress !== null) $out['Bindings.Email.Address'] = $this->bindingsEmailAddress;
        if ($this->bindingsEmailName !== null) $out['Bindings.Email.Name'] = $this->bindingsEmailName;
        return $out;
    }
}
