<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** List envelope for `GET /v1/Services` on the messaging host. */
final class MessagingServiceList implements Model
{
    /** @param list<MessagingService> $services */
    public function __construct(
        public readonly array $services,
        public readonly ?VoiceV1Meta $meta = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['services'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = MessagingService::fromArray($row);
            }
        }

        return new self(
            services: $items,
            meta: is_array($data['meta'] ?? null) ? VoiceV1Meta::fromArray($data['meta']) : null,
        );
    }
}
