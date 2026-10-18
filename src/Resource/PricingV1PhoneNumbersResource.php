<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingPhoneNumberCountry;
use VoiceML\Transport;

/** `$client->pricing->v1->phoneNumbers` — `.countries` only. */
final class PricingV1PhoneNumbersResource extends Resource
{
    public readonly PricingCountriesResource $countries;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->countries = new PricingCountriesResource(
            $transport,
            $this->v1Path('PhoneNumbers', 'Countries'),
            static fn (array $data): PricingPhoneNumberCountry => PricingPhoneNumberCountry::fromArray($data),
        );
    }
}
