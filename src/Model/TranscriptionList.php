<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class TranscriptionList extends Page
{
    /**
     * @param list<CallTranscription> $transcriptions
     */
    public function __construct(
        public readonly array $transcriptions = [],
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
        /** @var list<CallTranscription> $items */
        $items = [];
        if (isset($data['transcriptions']) && is_array($data['transcriptions'])) {
            foreach ($data['transcriptions'] as $row) {
                if (is_array($row)) {
                    $items[] = CallTranscription::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $numPages, $total, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            transcriptions: $items,
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
