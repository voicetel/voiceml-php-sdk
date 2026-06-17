<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Page of credentials within a CredentialList (spec name `SipCredentialListPage`). */
final class SipCredentialListPage implements Model
{
    /** @param list<SipCredential> $credentials */
    public function __construct(
        public readonly array $credentials,
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
        foreach ((array) ($data['credentials'] ?? []) as $row) {
            if (is_array($row)) $items[] = SipCredential::fromArray($row);
        }
        return new self(
            credentials: $items,
            page: (int) ($data['page'] ?? 0),
            pageSize: (int) ($data['page_size'] ?? 50),
            total: isset($data['total']) ? (int) $data['total'] : null,
            nextPageUri: isset($data['next_page_uri']) ? (string) $data['next_page_uri'] : null,
            previousPageUri: isset($data['previous_page_uri']) ? (string) $data['previous_page_uri'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
