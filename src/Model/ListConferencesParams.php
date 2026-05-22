<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Conferences`.
 */
final class ListConferencesParams
{
    public function __construct(
        public readonly ?string $friendlyName = null,
        public readonly ?string $status = null,
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
            'FriendlyName' => $this->friendlyName,
            'Status' => $this->status,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
