<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1Session;
use VoiceML\Model\AssistantsV1SessionList;
use VoiceML\Transport;

/** `/v1/Sessions` — read-only Assistants v1 Session list + fetch. */
final class AssistantsV1SessionsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1SessionList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Sessions', $query);
        return AssistantsV1SessionList::fromArray($raw);
    }

    public function fetch(string $sessionId): AssistantsV1Session
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Sessions/{$sessionId}");
        return AssistantsV1Session::fromArray($raw);
    }
}
