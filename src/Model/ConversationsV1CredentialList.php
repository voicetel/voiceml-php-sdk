<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Credentials` response. */
final class ConversationsV1CredentialList implements Model
{
    /** @param list<ConversationsV1Credential> $credentials */
    public function __construct(
        public readonly array $credentials,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['credentials'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1Credential::fromArray($row);
        }
        return new self(
            credentials: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
