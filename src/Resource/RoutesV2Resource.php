<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/** `$client->routesV2` — Twilio routes/v2 Inbound Processing Region API. */
final class RoutesV2Resource
{
    public readonly RoutesV2SipDomainsResource $sipDomains;
    public readonly RoutesV2PhoneNumbersResource $phoneNumbers;

    public function __construct(Transport $transport)
    {
        $this->sipDomains = new RoutesV2SipDomainsResource($transport);
        $this->phoneNumbers = new RoutesV2PhoneNumbersResource($transport);
    }
}
