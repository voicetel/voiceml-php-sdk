<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v2/Voice/Numbers/{DestinationNumber}` body. */
final class PricingVoiceNumberV2 implements Model
{
    /** @param list<PricingOutboundCallPriceWithOrigin> $outboundCallPrices */
    public function __construct(
        public readonly ?string $destinationNumber = null,
        public readonly ?string $originationNumber = null,
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $outboundCallPrices = [],
        public readonly ?PricingInboundCallPrice $inboundCallPrice = null,
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $outbound = [];
        foreach ((array) ($data['outbound_call_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $outbound[] = PricingOutboundCallPriceWithOrigin::fromArray($row);
            }
        }

        return new self(
            destinationNumber: isset($data['destination_number']) ? (string) $data['destination_number'] : null,
            originationNumber: isset($data['origination_number']) ? (string) $data['origination_number'] : null,
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            outboundCallPrices: $outbound,
            inboundCallPrice: is_array($data['inbound_call_price'] ?? null)
                ? PricingInboundCallPrice::fromArray($data['inbound_call_price'])
                : null,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
