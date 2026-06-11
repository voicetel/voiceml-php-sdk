<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * REST companion to the `<Pay>` TwiML verb.
 *
 * The wire response is deliberately minimal — runtime config (charge amount,
 * payment connector, valid card types, etc.) is captured server-side and not
 * echoed back, matching Twilio's documented shape. Tenant BYO is binding: the
 * account must have `pay_enabled = true` AND a `stripe_secret_key` set, or the
 * call fails 403.
 */
final class CallPayment implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $callSid,
        public readonly string $apiVersion,
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
            callSid: (string) ($data['call_sid'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
        );
    }
}
