<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class MessageList extends Page
{
    /**
     * @param list<Message> $messages
     */
    public function __construct(
        public readonly array $messages = [],
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
        /** @var list<Message> $messages */
        $messages = [];
        if (isset($data['messages']) && is_array($data['messages'])) {
            foreach ($data['messages'] as $row) {
                if (is_array($row)) {
                    $messages[] = Message::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $numPages, $total, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            messages: $messages,
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
