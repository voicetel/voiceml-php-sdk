<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Recordings list response.
 *
 * The account-scoped endpoint (`GET /Recordings`) returns the canonical Twilio fields
 * (`recordings/page/page_size/total`). Per-call (`GET /Calls/{sid}/Recordings`) and
 * per-conference (`GET /Conferences/{sid}/Recordings`) endpoints currently return only
 * `recordings` — the other pagination fields will be `null`.
 */
final class RecordingList implements Model
{
    /**
     * @param list<Recording> $recordings
     */
    public function __construct(
        public readonly array $recordings = [],
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?int $total = null,
        public readonly ?int $numPages = null,
        public readonly ?string $firstPageUri = null,
        public readonly ?string $nextPageUri = null,
        public readonly ?string $previousPageUri = null,
        public readonly ?string $uri = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<Recording> $items */
        $items = [];
        if (isset($data['recordings']) && is_array($data['recordings'])) {
            foreach ($data['recordings'] as $row) {
                if (is_array($row)) {
                    $items[] = Recording::fromArray($row);
                }
            }
        }
        return new self(
            recordings: $items,
            page: isset($data['page']) ? (int) $data['page'] : null,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : null,
            total: isset($data['total']) ? (int) $data['total'] : null,
            numPages: isset($data['num_pages']) ? (int) $data['num_pages'] : null,
            firstPageUri: isset($data['first_page_uri']) ? (string) $data['first_page_uri'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            previousPageUri: isset($data['previous_page_uri']) ? (string) $data['previous_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
