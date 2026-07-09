<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Inbound price leaf — `number_type` is `local` or `toll free`. */
final class PricingInboundCallPrice implements Model
{
    public function __construct(
        public readonly ?string $basePrice = null,
        public readonly ?string $currentPrice = null,
        public readonly ?string $numberType = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            basePrice: isset($data['base_price']) ? (string) $data['base_price'] : null,
            currentPrice: isset($data['current_price']) ? (string) $data['current_price'] : null,
            numberType: isset($data['number_type']) ? (string) $data['number_type'] : null,
        );
    }
}
