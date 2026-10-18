<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * An active SIP registration (`GET /SIP/Registrations`).
 */
final class SipRegistration implements Model
{
    /**
     * @param list<string> $contacts
     * @param array<string,mixed>|null $activeCall
     */
    public function __construct(
        public readonly string $aor,
        public readonly string $username,
        public readonly string $domain,
        public readonly string $domainSid,
        public readonly string $status,
        public readonly array $contacts = [],
        public readonly ?string $callerId = null,
        public readonly ?string $userAgent = null,
        public readonly ?string $lastRegistered = null,
        public readonly ?array $activeCall = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<string> $contacts */
        $contacts = isset($data['contacts']) && is_array($data['contacts'])
            ? array_map(static fn ($v): string => (string) $v, $data['contacts'])
            : [];
        /** @var array<string,mixed>|null $activeCall */
        $activeCall = isset($data['active_call']) && is_array($data['active_call']) ? $data['active_call'] : null;

        return new self(
            aor: (string) ($data['aor'] ?? ''),
            username: (string) ($data['username'] ?? ''),
            domain: (string) ($data['domain'] ?? ''),
            domainSid: (string) ($data['domain_sid'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            contacts: $contacts,
            callerId: isset($data['caller_id']) ? (string) $data['caller_id'] : null,
            userAgent: isset($data['user_agent']) ? (string) $data['user_agent'] : null,
            lastRegistered: isset($data['last_registered']) ? (string) $data['last_registered'] : null,
            activeCall: $activeCall,
        );
    }
}
