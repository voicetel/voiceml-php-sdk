<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingTrunkingNumber;
use VoiceML\Transport;

/**
 * `GET /v2/Trunking/Numbers/{DestinationNumber}?OriginationNumber=…`. Both
 * number values are URL-encoded (E.164 `+` → `%2B`).
 */
final class PricingTrunkingNumbersV2Resource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    public function fetch(string $destinationNumber, ?string $originationNumber = null): PricingTrunkingNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            '/v2/Trunking/Numbers/' . rawurlencode($destinationNumber),
            ['OriginationNumber' => $originationNumber],
        );
        return PricingTrunkingNumber::fromArray($raw);
    }
}
