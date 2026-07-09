<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Outbound price leaf carrying the origination prefixes it applies to (v2). */
final class PricingOutboundCallPriceWithOrigin implements Model
{
    /** @param list<string> $originationPrefixes */
    public function __construct(
        public readonly array $originationPrefixes = [],
        public readonly ?string $basePrice = null,
        public readonly ?string $currentPrice = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            originationPrefixes: array_map(
                static fn ($v): string => (string) $v,
                array_values((array) ($data['origination_prefixes'] ?? [])),
            ),
            basePrice: isset($data['base_price']) ? (string) $data['base_price'] : null,
            currentPrice: isset($data['current_price']) ? (string) $data['current_price'] : null,
        );
    }
}
