<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Calls/{sid}/Events` — always an empty list (compat stub).
 *
 * Canonical event source is the customer's StatusCallback URL.
 */
final class EventsList implements Model
{
    /**
     * @param list<mixed> $events
     */
    public function __construct(
        public readonly array $events = [],
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
        $items = isset($data['events']) && is_array($data['events'])
            ? array_values($data['events'])
            : [];
        return new self(
            events: $items,
            page: isset($data['page']) ? (int) $data['page'] : 0,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : 0,
            total: isset($data['total']) ? (int) $data['total'] : 0,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
