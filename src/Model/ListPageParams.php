<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Pagination query params for list endpoints that only expose Page/PageSize.
 */
final class ListPageParams
{
    public function __construct(
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
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
        ];
    }
}
