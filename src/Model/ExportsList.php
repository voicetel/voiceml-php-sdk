<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /Exports` response — the list of export days available for download.
 */
final class ExportsList implements Model
{
    /**
     * @param list<Export> $exports
     */
    public function __construct(
        public readonly array $exports = [],
        public readonly ?string $accountSid = null,
        public readonly ?string $uri = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<Export> $items */
        $items = [];
        if (isset($data['exports']) && is_array($data['exports'])) {
            foreach ($data['exports'] as $row) {
                if (is_array($row)) {
                    $items[] = Export::fromArray($row);
                }
            }
        }

        return new self(
            exports: $items,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
        );
    }
}
