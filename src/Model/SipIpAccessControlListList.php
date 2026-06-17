<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated /SIP/IpAccessControlLists response. */
final class SipIpAccessControlListList implements Model
{
    /** @param list<SipIpAccessControlList> $ipAccessControlLists */
    public function __construct(
        public readonly array $ipAccessControlLists,
        public readonly int $page,
        public readonly int $pageSize,
        public readonly ?int $total = null,
        public readonly ?string $nextPageUri = null,
        public readonly ?string $previousPageUri = null,
        public readonly ?string $uri = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['ip_access_control_lists'] ?? []) as $row) {
            if (is_array($row)) $items[] = SipIpAccessControlList::fromArray($row);
        }
        return new self(
            ipAccessControlLists: $items,
            page: (int) ($data['page'] ?? 0),
            pageSize: (int) ($data['page_size'] ?? 50),
            total: isset($data['total']) ? (int) $data['total'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            previousPageUri: isset($data['previous_page_uri']) ? (string) $data['previous_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
