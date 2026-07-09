<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/** `$client->pricing->v1` — Voice / Messaging / PhoneNumbers pricing (v1). */
final class PricingV1Resource
{
    public readonly PricingV1VoiceResource $voice;
    public readonly PricingV1MessagingResource $messaging;
    public readonly PricingV1PhoneNumbersResource $phoneNumbers;

    public function __construct(Transport $transport)
    {
        $this->voice = new PricingV1VoiceResource($transport);
        $this->messaging = new PricingV1MessagingResource($transport);
        $this->phoneNumbers = new PricingV1PhoneNumbersResource($transport);
    }
}
