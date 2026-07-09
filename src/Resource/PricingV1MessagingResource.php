<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingMessagingCountry;
use VoiceML\Transport;

/** `$client->pricing->v1->messaging` — `.countries` only. */
final class PricingV1MessagingResource
{
    public readonly PricingCountriesResource $countries;

    public function __construct(Transport $transport)
    {
        $this->countries = new PricingCountriesResource(
            $transport,
            '/v1/Messaging/Countries',
            static fn (array $data): PricingMessagingCountry => PricingMessagingCountry::fromArray($data),
        );
    }
}
