<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Conversations/{ConversationSid}/Webhooks`. */
final class CreateConversationsV1ConversationScopedWebhookRequest
{
    public function __construct(
        public readonly string $target,
        public readonly ?string $configurationUrl = null,
        public readonly ?string $configurationMethod = null,
        public readonly ?string $configurationFlowSid = null,
        public readonly ?int $configurationReplayAfter = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['Target' => $this->target];
        if ($this->configurationUrl !== null) $out['Configuration.Url'] = $this->configurationUrl;
        if ($this->configurationMethod !== null) $out['Configuration.Method'] = $this->configurationMethod;
        if ($this->configurationFlowSid !== null) $out['Configuration.FlowSid'] = $this->configurationFlowSid;
        if ($this->configurationReplayAfter !== null) $out['Configuration.ReplayAfter'] = $this->configurationReplayAfter;
        return $out;
    }
}
