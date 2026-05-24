<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Notifications` and `GET /Calls/{sid}/Notifications`.
 */
final class ListNotificationsParams
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $pageToken = null,
        public readonly ?int $log = null,
        public readonly ?string $messageDate = null,
        public readonly ?string $messageDateLt = null,
        public readonly ?string $messageDateGt = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return [
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
            'Log' => $this->log,
            'MessageDate' => $this->messageDate,
            'MessageDate<' => $this->messageDateLt,
            'MessageDate>' => $this->messageDateGt,
        ];
    }
}
