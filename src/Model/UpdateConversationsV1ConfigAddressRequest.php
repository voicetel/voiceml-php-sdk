<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Configuration/Addresses/{Sid}`. */
final class UpdateConversationsV1ConfigAddressRequest
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?bool $autoCreationEnabled = null,
        public readonly ?string $autoCreationType = null,
        public readonly ?string $autoCreationWebhookUrl = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->autoCreationEnabled !== null) $out['AutoCreation.Enabled'] = $this->autoCreationEnabled;
        if ($this->autoCreationType !== null) $out['AutoCreation.Type'] = $this->autoCreationType;
        if ($this->autoCreationWebhookUrl !== null) $out['AutoCreation.WebhookUrl'] = $this->autoCreationWebhookUrl;
        return $out;
    }
}
