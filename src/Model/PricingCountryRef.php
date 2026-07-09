<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A country reference in a pricing `Countries` list envelope. */
final class PricingCountryRef implements Model
{
    public function __construct(
        public readonly ?string $country = null,
        public readonly ?string $isoCountry = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            country: isset($data['country']) ? (string) $data['country'] : null,
            isoCountry: isset($data['iso_country']) ? (string) $data['iso_country'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
