<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingTrunkingCountry;
use VoiceML\Transport;

/** `$client->pricing->v2->trunking` — `.countries` + `.numbers`. */
final class PricingV2TrunkingResource
{
    public readonly PricingCountriesResource $countries;
    public readonly PricingTrunkingNumbersV2Resource $numbers;

    public function __construct(Transport $transport)
    {
        $this->countries = new PricingCountriesResource(
            $transport,
            '/v2/Trunking/Countries',
            static fn (array $data): PricingTrunkingCountry => PricingTrunkingCountry::fromArray($data),
        );
        $this->numbers = new PricingTrunkingNumbersV2Resource($transport);
    }
}
