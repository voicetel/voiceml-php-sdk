<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConfigAddress;
use VoiceML\Model\ConversationsV1ConfigAddressList;
use VoiceML\Model\CreateConversationsV1ConfigAddressRequest;
use VoiceML\Model\UpdateConversationsV1ConfigAddressRequest;
use VoiceML\Transport;

/** `/v1/Configuration/Addresses` — Conversations Configuration Addresses. */
final class ConversationsV1ConfigAddressesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateConversationsV1ConfigAddressRequest $body */
    public function create(array|CreateConversationsV1ConfigAddressRequest $body): ConversationsV1ConfigAddress
    {
        $form = $body instanceof CreateConversationsV1ConfigAddressRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/Configuration/Addresses', null, $form);
        return ConversationsV1ConfigAddress::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConfigAddressList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Configuration/Addresses', $query);
        return ConversationsV1ConfigAddressList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ConfigAddress
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Configuration/Addresses/{$sid}");
        return ConversationsV1ConfigAddress::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1ConfigAddressRequest $body */
    public function update(string $sid, array|UpdateConversationsV1ConfigAddressRequest $body = []): ConversationsV1ConfigAddress
    {
        $form = $body instanceof UpdateConversationsV1ConfigAddressRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/Configuration/Addresses/{$sid}", null, $form);
        return ConversationsV1ConfigAddress::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Configuration/Addresses/{$sid}");
    }
}
