<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v2/Trunking/Countries/{IsoCountry}` body. */
final class PricingTrunkingCountry implements Model
{
    /**
     * @param list<PricingOutboundPrefixPriceWithOrigin> $terminatingPrefixPrices
     * @param list<PricingInboundCallPrice>              $originatingCallPrices
     */
    public function __construct(
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $terminatingPrefixPrices = [],
        public readonly array $originatingCallPrices = [],
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $terminating = [];
        foreach ((array) ($data['terminating_prefix_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $terminating[] = PricingOutboundPrefixPriceWithOrigin::fromArray($row);
            }
        }
        $originating = [];
        foreach ((array) ($data['originating_call_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $originating[] = PricingInboundCallPrice::fromArray($row);
            }
        }

        return new self(
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            terminatingPrefixPrices: $terminating,
            originatingCallPrices: $originating,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
