<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/** `$client->pricing->v2` — Voice / Trunking pricing (v2). */
final class PricingV2Resource
{
    public readonly PricingV2VoiceResource $voice;
    public readonly PricingV2TrunkingResource $trunking;

    public function __construct(Transport $transport)
    {
        $this->voice = new PricingV2VoiceResource($transport);
        $this->trunking = new PricingV2TrunkingResource($transport);
    }
}
