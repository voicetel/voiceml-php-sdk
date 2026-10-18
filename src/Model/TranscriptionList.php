<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Transcriptions` / `GET /Recordings/{sid}/Transcriptions` list envelope.
 */
final class TranscriptionList extends Page
{
    /**
     * @param list<Transcription> $transcriptions
     */
    public function __construct(
        public readonly array $transcriptions = [],
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
        /** @var list<Transcription> $items */
        $items = [];
        if (isset($data['transcriptions']) && is_array($data['transcriptions'])) {
            foreach ($data['transcriptions'] as $row) {
                if (is_array($row)) {
                    $items[] = Transcription::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            transcriptions: $items,
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
