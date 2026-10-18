<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Notifications` / `GET /Calls/{sid}/Notifications` list envelope.
 */
final class NotificationsList extends Page
{
    /**
     * @param list<Notification> $notifications
     */
    public function __construct(
        public readonly array $notifications = [],
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
        /** @var list<Notification> $items */
        $items = [];
        if (isset($data['notifications']) && is_array($data['notifications'])) {
            foreach ($data['notifications'] as $row) {
                if (is_array($row)) {
                    $items[] = Notification::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            notifications: $items,
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
