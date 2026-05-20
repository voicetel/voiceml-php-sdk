<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class IncomingPhoneNumberList extends Page
{
    /**
     * @param list<IncomingPhoneNumber> $incomingPhoneNumbers
     */
    public function __construct(
        public readonly array $incomingPhoneNumbers = [],
        ?int $page = 0,
        ?int $pageSize = null,
        ?int $numPages = null,
        ?int $total = null,
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
            $numPages,
            $total,
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
        /** @var list<IncomingPhoneNumber> $rows */
        $rows = [];
        if (isset($data['incoming_phone_numbers']) && is_array($data['incoming_phone_numbers'])) {
            foreach ($data['incoming_phone_numbers'] as $row) {
                if (is_array($row)) {
                    $rows[] = IncomingPhoneNumber::fromArray($row);
                }
            }
        }
        [$page, $pageSize, $numPages, $total, $start, $end, $firstPageUri, $nextPageUri, $previousPageUri, $uri] = self::pageFields($data);
        return new self(
            incomingPhoneNumbers: $rows,
            page: $page,
            pageSize: $pageSize,
            numPages: $numPages,
            total: $total,
            start: $start,
            end: $end,
            firstPageUri: $firstPageUri,
            nextPageUri: $nextPageUri,
            previousPageUri: $previousPageUri,
            uri: $uri,
        );
    }
}
