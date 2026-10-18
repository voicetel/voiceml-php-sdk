<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Response of `POST /OutgoingCallerIds` — the verification-call handshake:
 * the API dials `phoneNumber` and the caller reads back `validationCode`.
 */
final class ValidationRequest implements Model
{
    public function __construct(
        public readonly string $accountSid,
        public readonly string $phoneNumber,
        public readonly string $friendlyName,
        public readonly string $validationCode,
        public readonly string $callSid,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: (string) ($data['account_sid'] ?? ''),
            phoneNumber: (string) ($data['phone_number'] ?? ''),
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            validationCode: (string) ($data['validation_code'] ?? ''),
            callSid: (string) ($data['call_sid'] ?? ''),
        );
    }
}
