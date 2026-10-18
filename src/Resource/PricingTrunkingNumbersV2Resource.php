<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingTrunkingNumber;

/**
 * `GET /v2/Trunking/Numbers/{DestinationNumber}?OriginationNumber=…`. Both
 * number values are URL-encoded (E.164 `+` → `%2B`).
 */
final class PricingTrunkingNumbersV2Resource extends Resource
{

    public function fetch(string $destinationNumber, ?string $originationNumber = null): PricingTrunkingNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v2Path('Trunking', 'Numbers', $destinationNumber),
            ['OriginationNumber' => $originationNumber],
        );
        return PricingTrunkingNumber::fromArray($raw);
    }
}
