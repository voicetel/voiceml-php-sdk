<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /SIP/Registrations` list envelope.
 */
final class SipRegistrationList extends Page
{
    /**
     * @param list<SipRegistration> $registrations
     */
    public function __construct(
        public readonly array $registrations = [],
        ?int $page = 0,
        ?int $pageSize = null,
        ?int $start = null,
        ?int $end = null,
        ?string $firstPageUri = null,
        ?string $nextPageUri = null,
        ?string $previousPageUri = null,
        ?string $uri = null,
    ) {
        parent::__construct(
            $page,
            $pageSize,
            $start,
            $end,
            $firstPageUri,
            $nextPageUri,
            $previousPageUri,
            $uri,
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<SipRegistration> $items */
        $items = [];
        if (isset($data['registrations']) && is_array($data['registrations'])) {
            foreach ($data['registrations'] as $row) {
                if (is_array($row)) {
                    $items[] = SipRegistration::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            registrations: $items,
            page: $page,
            pageSize: $pageSize,
            start: $start,
            end: $end,
            firstPageUri: $firstPageUri,
            nextPageUri: $nextPageUri,
            previousPageUri: $previousPageUri,
            uri: $uri,
        );
    }
}
