<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Configuration`. */
final class UpdateConversationsV1ConfigurationRequest
{
    public function __construct(
        public readonly ?string $defaultChatServiceSid = null,
        public readonly ?string $defaultMessagingServiceSid = null,
        public readonly ?string $defaultInactiveTimer = null,
        public readonly ?string $defaultClosedTimer = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->defaultChatServiceSid !== null) $out['DefaultChatServiceSid'] = $this->defaultChatServiceSid;
        if ($this->defaultMessagingServiceSid !== null) $out['DefaultMessagingServiceSid'] = $this->defaultMessagingServiceSid;
        if ($this->defaultInactiveTimer !== null) $out['DefaultInactiveTimer'] = $this->defaultInactiveTimer;
        if ($this->defaultClosedTimer !== null) $out['DefaultClosedTimer'] = $this->defaultClosedTimer;
        return $out;
    }
}
