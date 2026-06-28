<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Tool (`aia_tool_…`). */
final class AssistantsV1Tool implements Model
{
    /** @param array<string,mixed>|null $meta */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $type,
        public readonly ?string $description,
        public readonly ?bool $enabled,
        public readonly ?bool $requiresAuth,
        public readonly ?array $meta = null,
        public readonly ?string $url = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            description: isset($data['description']) ? (string) $data['description'] : null,
            enabled: isset($data['enabled']) ? (bool) $data['enabled'] : null,
            requiresAuth: isset($data['requires_auth']) ? (bool) $data['requires_auth'] : null,
            meta: isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
