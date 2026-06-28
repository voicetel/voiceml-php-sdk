<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Webhooks/{WebhookSid}`. */
final class UpdateConversationsV1ServiceConversationScopedWebhookRequest
{
    public function __construct(
        public readonly ?string $configurationUrl = null,
        public readonly ?string $configurationMethod = null,
        public readonly ?string $configurationFlowSid = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->configurationUrl !== null) $out['Configuration.Url'] = $this->configurationUrl;
        if ($this->configurationMethod !== null) $out['Configuration.Method'] = $this->configurationMethod;
        if ($this->configurationFlowSid !== null) $out['Configuration.FlowSid'] = $this->configurationFlowSid;
        return $out;
    }
}
