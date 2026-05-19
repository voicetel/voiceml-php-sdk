<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Calls/{sid}/Notifications` — always an empty list (compat stub).
 */
final class NotificationsList implements Model
{
    /**
     * @param list<mixed> $notifications
     */
    public function __construct(
        public readonly array $notifications = [],
        public readonly int $page = 0,
        public readonly int $pageSize = 0,
        public readonly int $total = 0,
        public readonly ?string $uri = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<mixed> $items */
        $items = isset($data['notifications']) && is_array($data['notifications'])
            ? array_values($data['notifications'])
            : [];
        return new self(
            notifications: $items,
            page: isset($data['page']) ? (int) $data['page'] : 0,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 0,
            total: isset($data['total']) ? (int) $data['total'] : 0,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
