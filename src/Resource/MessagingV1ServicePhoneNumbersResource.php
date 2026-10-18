<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AddMessagingServicePhoneNumberRequest;
use VoiceML\Model\MessagingServicePhoneNumber;
use VoiceML\Model\MessagingServicePhoneNumberList;
use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/PhoneNumbers` — a Messaging Service's
 * sender pool. Routed at the messaging product host by the client.
 */
final class MessagingV1ServicePhoneNumbersResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $chatServiceSid,
    ) {
        parent::__construct($transport);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): MessagingServicePhoneNumberList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v1Path('Services', $this->chatServiceSid, 'PhoneNumbers'),
            $query,
        );
        return MessagingServicePhoneNumberList::fromArray($raw);
    }

    public function create(AddMessagingServicePhoneNumberRequest $body): MessagingServicePhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->v1Path('Services', $this->chatServiceSid, 'PhoneNumbers'),
            null,
            $body->toForm(),
        );
        return MessagingServicePhoneNumber::fromArray($raw);
    }

    public function fetch(string $sid): MessagingServicePhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->v1Path('Services', $this->chatServiceSid, 'PhoneNumbers', $sid),
        );
        return MessagingServicePhoneNumber::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request(
            'DELETE',
            $this->v1Path('Services', $this->chatServiceSid, 'PhoneNumbers', $sid),
        );
    }
}
