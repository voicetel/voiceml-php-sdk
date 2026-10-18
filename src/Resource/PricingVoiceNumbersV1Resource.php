<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceNumber;

/** `GET /v1/Voice/Numbers/{Number}`. The number segment is URL-encoded. */
final class PricingVoiceNumbersV1Resource extends Resource
{

    public function fetch(string $number): PricingVoiceNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Voice', 'Numbers', $number));
        return PricingVoiceNumber::fromArray($raw);
    }
}
