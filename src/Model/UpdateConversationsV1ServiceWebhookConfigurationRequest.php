<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Configuration/Webhooks`. */
final class UpdateConversationsV1ServiceWebhookConfigurationRequest
{
    /** @param list<string>|null $filters */
    public function __construct(
        public readonly ?string $preWebhookUrl = null,
        public readonly ?string $postWebhookUrl = null,
        public readonly ?string $method = null,
        public readonly ?array $filters = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->preWebhookUrl !== null) $out['PreWebhookUrl'] = $this->preWebhookUrl;
        if ($this->postWebhookUrl !== null) $out['PostWebhookUrl'] = $this->postWebhookUrl;
        if ($this->method !== null) $out['Method'] = $this->method;
        if ($this->filters !== null) $out['Filters'] = $this->filters;
        return $out;
    }
}
