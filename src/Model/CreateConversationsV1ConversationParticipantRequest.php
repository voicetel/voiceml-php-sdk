<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Conversations/{ConversationSid}/Participants`. */
final class CreateConversationsV1ConversationParticipantRequest
{
    public function __construct(
        public readonly ?string $identity = null,
        public readonly ?string $attributes = null,
        public readonly ?string $roleSid = null,
        public readonly ?string $messagingBindingAddress = null,
        public readonly ?string $messagingBindingProxyAddress = null,
        public readonly ?string $messagingBindingProjectedAddress = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->identity !== null) $out['Identity'] = $this->identity;
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        if ($this->roleSid !== null) $out['RoleSid'] = $this->roleSid;
        if ($this->messagingBindingAddress !== null) $out['MessagingBinding.Address'] = $this->messagingBindingAddress;
        if ($this->messagingBindingProxyAddress !== null) $out['MessagingBinding.ProxyAddress'] = $this->messagingBindingProxyAddress;
        if ($this->messagingBindingProjectedAddress !== null) $out['MessagingBinding.ProjectedAddress'] = $this->messagingBindingProjectedAddress;
        return $out;
    }
}
