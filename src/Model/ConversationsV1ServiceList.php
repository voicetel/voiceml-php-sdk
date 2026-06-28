<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services` response. */
final class ConversationsV1ServiceList implements Model
{
    /** @param list<ConversationsV1Service> $services */
    public function __construct(
        public readonly array $services,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['services'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1Service::fromArray($row);
        }
        return new self(
            services: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
