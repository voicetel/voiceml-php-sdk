<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A phone number in a Messaging Service's sender pool
 * (`/v1/Services/{sid}/PhoneNumbers`).
 */
final class MessagingServicePhoneNumber implements Model
{
    /**
     * @param list<string> $capabilities
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $serviceSid,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $phoneNumber,
        public readonly string $countryCode,
        public readonly array $capabilities = [],
        public readonly string $url = '',
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<string> $capabilities */
        $capabilities = isset($data['capabilities']) && is_array($data['capabilities'])
            ? array_map(static fn ($v): string => (string) $v, $data['capabilities'])
            : [];

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            serviceSid: (string) ($data['service_sid'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            phoneNumber: (string) ($data['phone_number'] ?? ''),
            countryCode: (string) ($data['country_code'] ?? ''),
            capabilities: $capabilities,
            url: (string) ($data['url'] ?? ''),
        );
    }
}
