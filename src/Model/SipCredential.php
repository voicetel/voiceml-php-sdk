<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A single SIP-digest username + password — `CR…`. Password is write-only. */
final class SipCredential implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $credentialListSid,
        public readonly string $username,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            credentialListSid: (string) ($data['credential_list_sid'] ?? ''),
            username: (string) ($data['username'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
