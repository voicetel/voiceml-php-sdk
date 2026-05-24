<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for recording list endpoints.
 */
final class ListRecordingsParams
{
    public function __construct(
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateCreatedLt = null,
        public readonly ?string $dateCreatedGt = null,
        public readonly ?string $callSid = null,
        public readonly ?string $conferenceSid = null,
        public readonly ?bool $includeSoftDeleted = null,
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $pageToken = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return [
            'DateCreated' => $this->dateCreated,
            'DateCreated<' => $this->dateCreatedLt,
            'DateCreated>' => $this->dateCreatedGt,
            'CallSid' => $this->callSid,
            'ConferenceSid' => $this->conferenceSid,
            'IncludeSoftDeleted' => $this->includeSoftDeleted,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
