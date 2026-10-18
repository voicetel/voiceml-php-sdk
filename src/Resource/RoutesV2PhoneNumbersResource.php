<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\RoutesV2PhoneNumber;
use VoiceML\Model\UpdateRoutesV2PhoneNumberRequest;

/**
 * `/v2/PhoneNumbers/{PhoneNumber}` — Twilio routes/v2 Inbound Processing
 * Region for a phone number. Keyed by the E.164 number (or PN sid); the
 * account is resolved from HTTP Basic auth.
 */
final class RoutesV2PhoneNumbersResource extends Resource
{

    public function fetch(string $phoneNumber): RoutesV2PhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v2Path('PhoneNumbers', $phoneNumber));
        return RoutesV2PhoneNumber::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateRoutesV2PhoneNumberRequest $body */
    public function update(string $phoneNumber, array|UpdateRoutesV2PhoneNumberRequest $body = []): RoutesV2PhoneNumber
    {
        $form = $body instanceof UpdateRoutesV2PhoneNumberRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v2Path('PhoneNumbers', $phoneNumber), null, $form);
        return RoutesV2PhoneNumber::fromArray($raw);
    }
}
