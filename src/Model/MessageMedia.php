<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A media resource attached to a message (`/Messages/{sid}/Media`).
 */
final class MessageMedia implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $parentSid,
        public readonly ?string $contentType,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            parentSid: (string) ($data['parent_sid'] ?? ''),
            contentType: isset($data['content_type']) ? (string) $data['content_type'] : null,
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
