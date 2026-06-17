<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated CredentialListMapping list. */
final class SipCredentialListMappingList implements Model
{
    /** @param list<SipDomainMapping> $credentialListMappings */
    public function __construct(
        public readonly array $credentialListMappings,
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
        foreach ((array) ($data['credential_list_mappings'] ?? []) as $row) {
            if (is_array($row)) $items[] = SipDomainMapping::fromArray($row);
        }
        return new self(
            credentialListMappings: $items,
            page: (int) ($data['page'] ?? 0),
            pageSize: (int) ($data['page_size'] ?? 50),
            total: isset($data['total']) ? (int) $data['total'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
