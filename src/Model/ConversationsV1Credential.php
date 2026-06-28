<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Conversations push credential — Twilio Conversations v1 `CR…` resource. */
final class ConversationsV1Credential implements Model
{
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly string $type,
        public readonly ?string $friendlyName = null,
        public readonly ?string $sandbox = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            type: (string) ($data['type'] ?? ''),
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            sandbox: isset($data['sandbox']) ? (string) $data['sandbox'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
