<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Conversations Service — Twilio Conversations v1 `IS…` resource. */
final class ConversationsV1Service implements Model
{
    /** @param array<string,string>|null $links */
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly ?string $friendlyName = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
        );
    }
}
