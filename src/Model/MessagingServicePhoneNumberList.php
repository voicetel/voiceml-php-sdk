<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /v1/Services/{sid}/PhoneNumbers` list envelope (meta-nested, /v1 style).
 */
final class MessagingServicePhoneNumberList implements Model
{
    /**
     * @param list<MessagingServicePhoneNumber> $phoneNumbers
     */
    public function __construct(
        public readonly array $phoneNumbers = [],
        public readonly ?VoiceV1Meta $meta = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<MessagingServicePhoneNumber> $items */
        $items = [];
        if (isset($data['phone_numbers']) && is_array($data['phone_numbers'])) {
            foreach ($data['phone_numbers'] as $row) {
                if (is_array($row)) {
                    $items[] = MessagingServicePhoneNumber::fromArray($row);
                }
            }
        }
        $meta = isset($data['meta']) && is_array($data['meta']) ? VoiceV1Meta::fromArray($data['meta']) : null;

        return new self(phoneNumbers: $items, meta: $meta);
    }
}
