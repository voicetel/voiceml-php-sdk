<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /OutgoingCallerIds` list envelope.
 */
final class OutgoingCallerIdList extends Page
{
    /**
     * @param list<OutgoingCallerId> $outgoingCallerIds
     */
    public function __construct(
        public readonly array $outgoingCallerIds = [],
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
        /** @var list<OutgoingCallerId> $items */
        $items = [];
        if (isset($data['outgoing_caller_ids']) && is_array($data['outgoing_caller_ids'])) {
            foreach ($data['outgoing_caller_ids'] as $row) {
                if (is_array($row)) {
                    $items[] = OutgoingCallerId::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            outgoingCallerIds: $items,
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
