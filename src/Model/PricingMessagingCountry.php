<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v1/Messaging/Countries/{IsoCountry}` body. */
final class PricingMessagingCountry implements Model
{
    /**
     * @param list<PricingOutboundSMSPrice>  $outboundSmsPrices
     * @param list<PricingInboundCallPrice>  $inboundSmsPrices
     */
    public function __construct(
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $outboundSmsPrices = [],
        public readonly array $inboundSmsPrices = [],
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $outbound = [];
        foreach ((array) ($data['outbound_sms_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $outbound[] = PricingOutboundSMSPrice::fromArray($row);
            }
        }
        $inbound = [];
        foreach ((array) ($data['inbound_sms_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $inbound[] = PricingInboundCallPrice::fromArray($row);
            }
        }

        return new self(
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            outboundSmsPrices: $outbound,
            inboundSmsPrices: $inbound,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
