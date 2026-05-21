<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Conferences/{sid}/Participants`.
 */
final class ListParticipantsParams
{
    public function __construct(
        public readonly ?bool $muted = null,
        public readonly ?bool $hold = null,
        public readonly ?bool $coaching = null,
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
            'Muted' => $this->muted,
            'Hold' => $this->hold,
            'Coaching' => $this->coaching,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
        ];
    }
}
