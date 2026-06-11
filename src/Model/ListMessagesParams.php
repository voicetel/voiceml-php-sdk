<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Messages`. Twilio's documented filter set
 * (`To`, `From`, `DateSent` eq/gt/lt) plus standard pagination.
 */
final class ListMessagesParams
{
    public function __construct(
        public readonly ?string $to = null,
        public readonly ?string $from = null,
        public readonly ?string $dateSent = null,
        public readonly ?string $dateSentLt = null,
        public readonly ?string $dateSentGt = null,
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $pageToken = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return [
            'To' => $this->to,
            'From' => $this->from,
            'DateSent' => $this->dateSent,
            'DateSent<' => $this->dateSentLt,
            'DateSent>' => $this->dateSentGt,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
