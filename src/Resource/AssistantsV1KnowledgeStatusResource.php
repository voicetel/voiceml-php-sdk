<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1KnowledgeStatus;
use VoiceML\Transport;

/** `/v1/Knowledge/{id}/Status` — read-only ingestion-status singleton. */
final class AssistantsV1KnowledgeStatusResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $knowledgeId,
    ) {
    }

    public function fetch(): AssistantsV1KnowledgeStatus
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/Knowledge/{$this->knowledgeId}/Status");
        return AssistantsV1KnowledgeStatus::fromArray($raw);
    }
}
