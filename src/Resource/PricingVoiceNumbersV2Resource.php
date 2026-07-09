<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceNumberV2;
use VoiceML\Transport;

/**
 * `GET /v2/Voice/Numbers/{DestinationNumber}?OriginationNumber=…`. Both number
 * values are URL-encoded (E.164 `+` → `%2B`).
 */
final class PricingVoiceNumbersV2Resource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    public function fetch(string $destinationNumber, ?string $originationNumber = null): PricingVoiceNumberV2
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            '/v2/Voice/Numbers/' . rawurlencode($destinationNumber),
            ['OriginationNumber' => $originationNumber],
        );
        return PricingVoiceNumberV2::fromArray($raw);
    }
}
