<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Applications`.
 */
final class ListApplicationsParams
{
    public function __construct(
        public readonly ?string $friendlyName = null,
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
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
