<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Outbound price leaf (no origination context). */
final class PricingOutboundCallPrice implements Model
{
    public function __construct(
        public readonly ?string $basePrice = null,
        public readonly ?string $currentPrice = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            basePrice: isset($data['base_price']) ? (string) $data['base_price'] : null,
            currentPrice: isset($data['current_price']) ? (string) $data['current_price'] : null,
        );
    }
}
