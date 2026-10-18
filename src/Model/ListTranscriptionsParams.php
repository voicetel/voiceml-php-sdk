<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for the account- and recording-scoped transcription lists
 * (`GET /Transcriptions`, `GET /Recordings/{sid}/Transcriptions`).
 */
final class ListTranscriptionsParams
{
    public function __construct(
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateCreatedLt = null,
        public readonly ?string $dateCreatedGt = null,
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
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
