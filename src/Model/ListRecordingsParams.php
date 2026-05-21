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
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
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
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
        ];
    }
}
