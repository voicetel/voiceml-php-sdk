<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v1/Voice/Countries/{IsoCountry}` body. */
final class PricingVoiceCountry implements Model
{
    /**
     * @param list<PricingOutboundPrefixPrice> $outboundPrefixPrices
     * @param list<PricingInboundCallPrice>    $inboundCallPrices
     */
    public function __construct(
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $outboundPrefixPrices = [],
        public readonly array $inboundCallPrices = [],
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $outbound = [];
        foreach ((array) ($data['outbound_prefix_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $outbound[] = PricingOutboundPrefixPrice::fromArray($row);
            }
        }
        $inbound = [];
        foreach ((array) ($data['inbound_call_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $inbound[] = PricingInboundCallPrice::fromArray($row);
            }
        }

        return new self(
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            outboundPrefixPrices: $outbound,
            inboundCallPrices: $inbound,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
