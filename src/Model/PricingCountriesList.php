<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Shared list envelope returned by every pricing `Countries.list()`. */
final class PricingCountriesList implements Model
{
    /** @param list<PricingCountryRef> $countries */
    public function __construct(
        public readonly array $countries,
        public readonly ?VoiceV1Meta $meta = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['countries'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = PricingCountryRef::fromArray($row);
            }
        }

        return new self(
            countries: $items,
            meta: is_array($data['meta'] ?? null) ? VoiceV1Meta::fromArray($data['meta']) : null,
        );
    }
}
