<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->pricing` — Twilio Pricing v1/v2 (pricing.twilio.com) family.
 *
 * Read-only `GET`s. VoiceML has no dedicated pricing subdomain, so the whole
 * family stays on the default host (`voiceml.voicetel.com`). Layout::
 *
 *   pricing->v1->voice->countries->list / fetch
 *   pricing->v1->voice->numbers->fetch
 *   pricing->v1->messaging->countries->list / fetch
 *   pricing->v1->phoneNumbers->countries->list / fetch
 *   pricing->v2->voice->countries->list / fetch
 *   pricing->v2->voice->numbers->fetch
 *   pricing->v2->trunking->countries->list / fetch
 *   pricing->v2->trunking->numbers->fetch
 */
final class PricingResource
{
    public readonly PricingV1Resource $v1;
    public readonly PricingV2Resource $v2;

    public function __construct(Transport $transport)
    {
        $this->v1 = new PricingV1Resource($transport);
        $this->v2 = new PricingV2Resource($transport);
    }
}
