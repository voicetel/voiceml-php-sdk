<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Outbound price keyed by origination + destination prefixes (v2). */
final class PricingOutboundPrefixPriceWithOrigin implements Model
{
    /**
     * @param list<string> $originationPrefixes
     * @param list<string> $destinationPrefixes
     */
    public function __construct(
        public readonly array $originationPrefixes = [],
        public readonly array $destinationPrefixes = [],
        public readonly ?string $basePrice = null,
        public readonly ?string $currentPrice = null,
        public readonly ?string $friendlyName = null,
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
            destinationPrefixes: array_map(
                static fn ($v): string => (string) $v,
                array_values((array) ($data['destination_prefixes'] ?? [])),
            ),
            basePrice: isset($data['base_price']) ? (string) $data['base_price'] : null,
            currentPrice: isset($data['current_price']) ? (string) $data['current_price'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
        );
    }
}
