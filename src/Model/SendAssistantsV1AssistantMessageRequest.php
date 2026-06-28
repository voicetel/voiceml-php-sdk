<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Assistants/{id}/Messages`. JSON wire format. */
final class SendAssistantsV1AssistantMessageRequest
{
    public function __construct(
        public readonly string $identity,
        public readonly string $body,
        public readonly ?string $sessionId = null,
        public readonly ?string $webhook = null,
        public readonly ?string $mode = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [
            'identity' => $this->identity,
            'body' => $this->body,
        ];
        if ($this->sessionId !== null) {
            $out['session_id'] = $this->sessionId;
        }
        if ($this->webhook !== null) {
            $out['webhook'] = $this->webhook;
        }
        if ($this->mode !== null) {
            $out['mode'] = $this->mode;
        }
        return $out;
    }
}
