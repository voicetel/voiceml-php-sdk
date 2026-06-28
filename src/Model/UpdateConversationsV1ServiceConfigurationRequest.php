<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Configuration`. */
final class UpdateConversationsV1ServiceConfigurationRequest
{
    public function __construct(
        public readonly ?string $defaultChatServiceRoleSid = null,
        public readonly ?string $defaultConversationCreatorRoleSid = null,
        public readonly ?string $defaultConversationRoleSid = null,
        public readonly ?bool $reachabilityEnabled = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->defaultChatServiceRoleSid !== null) $out['DefaultChatServiceRoleSid'] = $this->defaultChatServiceRoleSid;
        if ($this->defaultConversationCreatorRoleSid !== null) $out['DefaultConversationCreatorRoleSid'] = $this->defaultConversationCreatorRoleSid;
        if ($this->defaultConversationRoleSid !== null) $out['DefaultConversationRoleSid'] = $this->defaultConversationRoleSid;
        if ($this->reachabilityEnabled !== null) $out['ReachabilityEnabled'] = $this->reachabilityEnabled;
        return $out;
    }
}
