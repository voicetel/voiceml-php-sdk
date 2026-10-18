<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Usage/Records[/{Window}]` list envelope.
 */
final class UsageRecordList extends Page
{
    /**
     * @param list<UsageRecord> $usageRecords
     */
    public function __construct(
        public readonly array $usageRecords = [],
        ?int $page = 0,
        ?int $pageSize = null,
        ?int $start = null,
        ?int $end = null,
        ?string $firstPageUri = null,
        ?string $nextPageUri = null,
        ?string $previousPageUri = null,
        ?string $uri = null,
    ) {
        parent::__construct(
            $page,
            $pageSize,
            $start,
            $end,
            $firstPageUri,
            $nextPageUri,
            $previousPageUri,
            $uri,
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<UsageRecord> $items */
        $items = [];
        if (isset($data['usage_records']) && is_array($data['usage_records'])) {
            foreach ($data['usage_records'] as $row) {
                if (is_array($row)) {
                    $items[] = UsageRecord::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            usageRecords: $items,
            page: $page,
            pageSize: $pageSize,
            start: $start,
            end: $end,
            firstPageUri: $firstPageUri,
            nextPageUri: $nextPageUri,
            previousPageUri: $previousPageUri,
            uri: $uri,
        );
    }
}
