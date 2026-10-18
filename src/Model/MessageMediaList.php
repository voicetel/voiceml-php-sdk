<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Messages/{sid}/Media` list envelope.
 */
final class MessageMediaList extends Page
{
    /**
     * @param list<MessageMedia> $mediaList
     */
    public function __construct(
        public readonly array $mediaList = [],
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
        /** @var list<MessageMedia> $items */
        $items = [];
        if (isset($data['media_list']) && is_array($data['media_list'])) {
            foreach ($data['media_list'] as $row) {
                if (is_array($row)) {
                    $items[] = MessageMedia::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            mediaList: $items,
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
