<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Session — a conversation between an identity and an Assistant. */
final class AssistantsV1Session implements Model
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $accountSid,
        public readonly ?string $assistantId,
        public readonly ?bool $verified,
        public readonly ?string $identity,
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
            verified: isset($data['verified']) ? (bool) $data['verified'] : null,
            identity: isset($data['identity']) ? (string) $data['identity'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
