<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated IpAccessControlListMapping list. */
final class SipIpAccessControlListMappingList implements Model
{
    /** @param list<SipDomainMapping> $ipAccessControlListMappings */
    public function __construct(
        public readonly array $ipAccessControlListMappings,
        public readonly int $page,
        public readonly int $pageSize,
        public readonly ?int $total = null,
        public readonly ?string $nextPageUri = null,
        public readonly ?string $uri = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['ip_access_control_list_mappings'] ?? []) as $row) {
            if (is_array($row)) $items[] = SipDomainMapping::fromArray($row);
        }
        return new self(
            ipAccessControlListMappings: $items,
            page: (int) ($data['page'] ?? 0),
            pageSize: (int) ($data['page_size'] ?? 50),
            total: isset($data['total']) ? (int) $data['total'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
