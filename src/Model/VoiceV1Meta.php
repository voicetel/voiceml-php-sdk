<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Standard list-envelope `meta:` sub-object shared by every /v1/ list response
 * (Voice v1 and Conversations v1). Unlike the flattened 2010-04-01 envelope,
 * the /v1/ surface nests pagination links inside `meta`.
 */
final class VoiceV1Meta implements Model
{
    public function __construct(
        public readonly ?string $firstPageUrl = null,
        public readonly ?string $nextPageUrl = null,
        public readonly ?string $previousPageUrl = null,
        public readonly ?string $url = null,
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $key = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            firstPageUrl: isset($data['first_page_url']) ? (string) $data['first_page_url'] : null,
            nextPageUrl: isset($data['next_page_url']) ? (string) $data['next_page_url'] : null,
            previousPageUrl: isset($data['previous_page_url']) ? (string) $data['previous_page_url'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            page: isset($data['page']) ? (int) $data['page'] : null,
            pageSize: isset($data['page_size']) ? (int) $data['page_size'] : null,
            key: isset($data['key']) ? (string) $data['key'] : null,
        );
    }
}
