<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingPhoneNumberCountry;
use VoiceML\Transport;

/** `$client->pricing->v1->phoneNumbers` — `.countries` only. */
final class PricingV1PhoneNumbersResource
{
    public readonly PricingCountriesResource $countries;

    public function __construct(Transport $transport)
    {
        $this->countries = new PricingCountriesResource(
            $transport,
            '/v1/PhoneNumbers/Countries',
            static fn (array $data): PricingPhoneNumberCountry => PricingPhoneNumberCountry::fromArray($data),
        );
    }
}
