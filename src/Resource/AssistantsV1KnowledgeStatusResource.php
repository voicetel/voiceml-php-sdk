<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\AssistantsV1KnowledgeStatus;
use VoiceML\Transport;

/** `/v1/Knowledge/{id}/Status` — read-only ingestion-status singleton. */
final class AssistantsV1KnowledgeStatusResource extends Resource
{
    public function __construct(
        Transport $transport,
        private readonly string $knowledgeId,
    ) {
        parent::__construct($transport);
    }

    public function fetch(): AssistantsV1KnowledgeStatus
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Knowledge', $this->knowledgeId, 'Status'));
        return AssistantsV1KnowledgeStatus::fromArray($raw);
    }
}
