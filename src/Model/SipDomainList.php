<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated /SIP/Domains response. */
final class SipDomainList implements Model
{
    /** @param list<SipDomain> $domains */
    public function __construct(
        public readonly array $domains,
        public readonly int $page,
        public readonly int $pageSize,
        public readonly ?int $start = null,
        public readonly ?int $end = null,
        public readonly ?string $firstPageUri = null,
        public readonly ?string $nextPageUri = null,
        public readonly ?string $previousPageUri = null,
        public readonly ?string $uri = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['domains'] ?? []) as $row) {
            if (is_array($row)) $items[] = SipDomain::fromArray($row);
        }
        return new self(
            domains: $items,
            page: (int) ($data['page'] ?? 0),
            pageSize: (int) ($data['page_size'] ?? 50),
            start: isset($data['start']) ? (int) $data['start'] : null,
            end: isset($data['end']) ? (int) $data['end'] : null,
            firstPageUri: isset($data['first_page_uri']) ? (string) $data['first_page_uri'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            previousPageUri: isset($data['previous_page_uri']) ? (string) $data['previous_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
