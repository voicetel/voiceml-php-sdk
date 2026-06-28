<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services`. */
final class CreateConversationsV1ServiceRequest
{
    public function __construct(
        public readonly string $friendlyName,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return ['FriendlyName' => $this->friendlyName];
    }
}
