<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Message — produced when a Session sends/receives content. */
final class AssistantsV1Message implements Model
{
    /**
     * @param array<string,mixed>|null $content
     * @param array<string,mixed>|null $meta
     */
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $accountSid,
        public readonly ?string $assistantId,
        public readonly ?string $sessionId,
        public readonly ?string $identity,
        public readonly ?string $role,
        public readonly ?array $content = null,
        public readonly ?array $meta = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (string) $data['id'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            assistantId: isset($data['assistant_id']) ? (string) $data['assistant_id'] : null,
            sessionId: isset($data['session_id']) ? (string) $data['session_id'] : null,
            identity: isset($data['identity']) ? (string) $data['identity'] : null,
            role: isset($data['role']) ? (string) $data['role'] : null,
            content: isset($data['content']) && is_array($data['content']) ? $data['content'] : null,
            meta: isset($data['meta']) && is_array($data['meta']) ? $data['meta'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
