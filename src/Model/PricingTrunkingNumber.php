<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v2/Trunking/Numbers/{DestinationNumber}` body. */
final class PricingTrunkingNumber implements Model
{
    /** @param list<PricingOutboundPrefixPriceWithOrigin> $terminatingPrefixPrices */
    public function __construct(
        public readonly ?string $destinationNumber = null,
        public readonly ?string $originationNumber = null,
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $terminatingPrefixPrices = [],
        public readonly ?PricingInboundCallPrice $originatingCallPrice = null,
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

        return new self(
            destinationNumber: isset($data['destination_number']) ? (string) $data['destination_number'] : null,
            originationNumber: isset($data['origination_number']) ? (string) $data['origination_number'] : null,
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            terminatingPrefixPrices: $terminating,
            originatingCallPrice: is_array($data['originating_call_price'] ?? null)
                ? PricingInboundCallPrice::fromArray($data['originating_call_price'])
                : null,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
