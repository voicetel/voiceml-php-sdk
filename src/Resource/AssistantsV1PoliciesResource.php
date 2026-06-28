<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1PolicyList;
use VoiceML\Transport;

/** `/v1/Policies` — read-only Assistants v1 Policy list. */
final class AssistantsV1PoliciesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): AssistantsV1PolicyList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Policies', $query);
        return AssistantsV1PolicyList::fromArray($raw);
    }
}
