<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceCountry;
use VoiceML\Transport;

/** `$client->pricing->v1->voice` — `.countries` + `.numbers`. */
final class PricingV1VoiceResource
{
    public readonly PricingCountriesResource $countries;
    public readonly PricingVoiceNumbersV1Resource $numbers;

    public function __construct(Transport $transport)
    {
        $this->countries = new PricingCountriesResource(
            $transport,
            '/v1/Voice/Countries',
            static fn (array $data): PricingVoiceCountry => PricingVoiceCountry::fromArray($data),
        );
        $this->numbers = new PricingVoiceNumbersV1Resource($transport);
    }
}
