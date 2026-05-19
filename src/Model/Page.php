<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Twilio-shape pagination envelope.
 *
 * Field names match the wire shape exactly. Subclasses (CallList, ConferenceList, ...) add the
 * concrete resource-list property (`calls`, `conferences`, ...) and call `Page::populate()` from
 * their own `fromArray()` factory.
 */
abstract class Page implements Model
{
    public function __construct(
        public readonly ?int $page = 0,
        public readonly ?int $pageSize = null,
        public readonly ?int $numPages = null,
        public readonly ?int $total = null,
        public readonly ?int $start = null,
        public readonly ?int $end = null,
        public readonly ?string $firstPageUri = null,
        public readonly ?string $nextPageUri = null,
        public readonly ?string $previousPageUri = null,
        public readonly ?string $uri = null,
    ) {
    }

    /**
     * Pull the standard envelope fields from a wire-shape array. Used by every list factory.
     *
     * @param array<string,mixed> $data
     *
     * @return array{0:?int,1:?int,2:?int,3:?int,4:?int,5:?int,6:?string,7:?string,8:?string,9:?string}
     */
    protected static function pageFields(array $data): array
    {
        return [
            isset($data['page']) ? (int) $data['page'] : null,
            isset($data['page_size']) ? (int) $data['page_size'] : null,
            isset($data['num_pages']) ? (int) $data['num_pages'] : null,
            isset($data['total']) ? (int) $data['total'] : null,
            isset($data['start']) ? (int) $data['start'] : null,
            isset($data['end']) ? (int) $data['end'] : null,
            isset($data['first_page_uri']) ? (string) $data['first_page_uri'] : null,
            isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            isset($data['previous_page_uri']) ? (string) $data['previous_page_uri'] : null,
            isset($data['uri']) ? (string) $data['uri'] : null,
        ];
    }
}
