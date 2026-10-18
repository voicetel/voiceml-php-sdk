<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\PricingMessagingCountry;
use VoiceML\Transport;

/** `$client->pricing->v1->messaging` — `.countries` only. */
final class PricingV1MessagingResource extends Resource
{
    public readonly PricingCountriesResource $countries;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->countries = new PricingCountriesResource(
            $transport,
            $this->v1Path('Messaging', 'Countries'),
            static fn (array $data): PricingMessagingCountry => PricingMessagingCountry::fromArray($data),
        );
    }
}
