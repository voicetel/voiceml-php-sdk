<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for the Usage Records list endpoints (`/Usage/Records` and
 * the eight window variants).
 */
final class ListUsageRecordsParams
{
    public function __construct(
        public readonly ?string $category = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?bool $includeSubaccounts = null,
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
            'Category' => $this->category,
            'StartDate' => $this->startDate,
            'EndDate' => $this->endDate,
            'IncludeSubaccounts' => $this->includeSubaccounts,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
