<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingVoiceCountryV2;
use VoiceML\Transport;

/** `$client->pricing->v2->voice` — `.countries` + `.numbers`. */
final class PricingV2VoiceResource
{
    public readonly PricingCountriesResource $countries;
    public readonly PricingVoiceNumbersV2Resource $numbers;

    public function __construct(Transport $transport)
    {
        $this->countries = new PricingCountriesResource(
            $transport,
            '/v2/Voice/Countries',
            static fn (array $data): PricingVoiceCountryV2 => PricingVoiceCountryV2::fromArray($data),
        );
        $this->numbers = new PricingVoiceNumbersV2Resource($transport);
    }
}
