<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceBinding;
use VoiceML\Model\ConversationsV1ServiceBindingList;
use VoiceML\Transport;

/**
 * Read/delete-only `/v1/Services/{ChatServiceSid}/Bindings`.
 * Push Bindings are system-created on device registration; the API exposes
 * list/fetch/delete only.
 */
final class ConversationsV1ServiceBindingsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceBindingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Bindings", $query);
        return ConversationsV1ServiceBindingList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceBinding
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Services/{$this->chatServiceSid}/Bindings/{$sid}");
        return ConversationsV1ServiceBinding::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/Services/{$this->chatServiceSid}/Bindings/{$sid}");
    }
}
