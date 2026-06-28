<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Messages/{MessageSid}`. */
final class UpdateConversationsV1ServiceConversationMessageRequest
{
    public function __construct(
        public readonly ?string $author = null,
        public readonly ?string $body = null,
        public readonly ?string $attributes = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->author !== null) $out['Author'] = $this->author;
        if ($this->body !== null) $out['Body'] = $this->body;
        if ($this->attributes !== null) $out['Attributes'] = $this->attributes;
        return $out;
    }
}
