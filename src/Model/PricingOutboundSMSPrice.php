<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Outbound SMS price for a carrier (MCC/MNC), with its per-number-type prices. */
final class PricingOutboundSMSPrice implements Model
{
    /** @param list<PricingInboundCallPrice> $prices */
    public function __construct(
        public readonly ?string $carrier = null,
        public readonly ?string $mcc = null,
        public readonly ?string $mnc = null,
        public readonly array $prices = [],
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $prices = [];
        foreach ((array) ($data['prices'] ?? []) as $row) {
            if (is_array($row)) {
                $prices[] = PricingInboundCallPrice::fromArray($row);
            }
        }

        return new self(
            carrier: isset($data['carrier']) ? (string) $data['carrier'] : null,
            mcc: isset($data['mcc']) ? (string) $data['mcc'] : null,
            mnc: isset($data['mnc']) ? (string) $data['mnc'] : null,
            prices: $prices,
        );
    }
}
