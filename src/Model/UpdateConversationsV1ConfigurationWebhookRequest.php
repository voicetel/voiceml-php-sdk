<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Configuration/Webhooks`. */
final class UpdateConversationsV1ConfigurationWebhookRequest
{
    /** @param list<string>|null $filters */
    public function __construct(
        public readonly ?string $method = null,
        public readonly ?array $filters = null,
        public readonly ?string $preWebhookUrl = null,
        public readonly ?string $postWebhookUrl = null,
        public readonly ?string $target = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->method !== null) $out['Method'] = $this->method;
        if ($this->filters !== null) $out['Filters'] = $this->filters;
        if ($this->preWebhookUrl !== null) $out['PreWebhookUrl'] = $this->preWebhookUrl;
        if ($this->postWebhookUrl !== null) $out['PostWebhookUrl'] = $this->postWebhookUrl;
        if ($this->target !== null) $out['Target'] = $this->target;
        return $out;
    }
}
