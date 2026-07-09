<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** `GET /v1/PhoneNumbers/Countries/{IsoCountry}` body. */
final class PricingPhoneNumberCountry implements Model
{
    /** @param list<PricingPhoneNumberPrice> $phoneNumberPrices */
    public function __construct(
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly array $phoneNumberPrices = [],
        public readonly ?string $priceUnit = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $prices = [];
        foreach ((array) ($data['phone_number_prices'] ?? []) as $row) {
            if (is_array($row)) {
                $prices[] = PricingPhoneNumberPrice::fromArray($row);
            }
        }

        return new self(
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            phoneNumberPrices: $prices,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
