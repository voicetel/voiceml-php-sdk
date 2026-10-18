<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceNumberV2;

/**
 * `GET /v2/Voice/Numbers/{DestinationNumber}?OriginationNumber=…`. Both number
 * values are URL-encoded (E.164 `+` → `%2B`).
 */
final class PricingVoiceNumbersV2Resource extends Resource
{

    public function fetch(string $destinationNumber, ?string $originationNumber = null): PricingVoiceNumberV2
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v2Path('Voice', 'Numbers', $destinationNumber),
            ['OriginationNumber' => $originationNumber],
        );
        return PricingVoiceNumberV2::fromArray($raw);
    }
}
