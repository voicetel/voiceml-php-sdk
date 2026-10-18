<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Calls/{sid}/Events` list envelope.
 */
final class EventsList extends Page
{
    /**
     * @param list<CallEvent> $events
     */
    public function __construct(
        public readonly array $events = [],
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
        /** @var list<CallEvent> $items */
        $items = [];
        if (isset($data['events']) && is_array($data['events'])) {
            foreach ($data['events'] as $row) {
                if (is_array($row)) {
                    $items[] = CallEvent::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            events: $items,
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
