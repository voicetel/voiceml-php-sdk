<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceNumber;
use VoiceML\Transport;

/** `GET /v1/Voice/Numbers/{Number}`. The number segment is URL-encoded. */
final class PricingVoiceNumbersV1Resource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    public function fetch(string $number): PricingVoiceNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Voice/Numbers/' . rawurlencode($number));
        return PricingVoiceNumber::fromArray($raw);
    }
}
