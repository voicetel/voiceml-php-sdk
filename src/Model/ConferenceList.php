<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class ConferenceList extends Page
{
    /**
     * @param list<Conference> $conferences
     */
    public function __construct(
        public readonly array $conferences = [],
        ?int $page = 0,
        ?int $pageSize = null,
        ?int $numPages = null,
        ?int $total = null,
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
            $numPages,
            $total,
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
        /** @var list<Conference> $conferences */
        $conferences = [];
        if (isset($data['conferences']) && is_array($data['conferences'])) {
            foreach ($data['conferences'] as $row) {
                if (is_array($row)) {
                    $conferences[] = Conference::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $numPages, $total, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            conferences: $conferences,
            page: $page,
            pageSize: $pageSize,
            numPages: $numPages,
            total: $total,
            start: $start,
            end: $end,
            firstPageUri: $firstPageUri,
            nextPageUri: $nextPageUri,
            previousPageUri: $previousPageUri,
            uri: $uri,
        );
    }
}
