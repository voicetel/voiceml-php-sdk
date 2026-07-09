<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v1/Voice/Numbers/{Number}` body. */
final class PricingVoiceNumber implements Model
{
    public function __construct(
        public readonly ?string $number = null,
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly ?PricingOutboundCallPrice $outboundCallPrice = null,
        public readonly ?PricingInboundCallPrice $inboundCallPrice = null,
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            number: isset($data['number']) ? (string) $data['number'] : null,
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            outboundCallPrice: is_array($data['outbound_call_price'] ?? null)
                ? PricingOutboundCallPrice::fromArray($data['outbound_call_price'])
                : null,
            inboundCallPrice: is_array($data['inbound_call_price'] ?? null)
                ? PricingInboundCallPrice::fromArray($data['inbound_call_price'])
                : null,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
