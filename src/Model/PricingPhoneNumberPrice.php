<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Monthly rental price for a phone-number type. */
final class PricingPhoneNumberPrice implements Model
{
    public function __construct(
        public readonly ?string $numberType = null,
        public readonly ?string $basePrice = null,
        public readonly ?string $currentPrice = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            numberType: isset($data['number_type']) ? (string) $data['number_type'] : null,
            basePrice: isset($data['base_price']) ? (string) $data['base_price'] : null,
            currentPrice: isset($data['current_price']) ? (string) $data['current_price'] : null,
        );
    }
}
