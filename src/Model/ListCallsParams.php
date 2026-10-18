<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /Calls`. The spec declares `StartTime`, `StartTime<`
 * and `StartTime>` (same for `EndTime`) — the legacy `StartTime>=` /
 * `StartTime<=` operators were removed from the SDK.
 */
final class ListCallsParams
{
    public function __construct(
        public readonly ?string $to = null,
        public readonly ?string $from = null,
        public readonly ?string $status = null,
        public readonly ?string $parentCallSid = null,
        public readonly ?string $startTime = null,
        public readonly ?string $startTimeLt = null,
        public readonly ?string $startTimeGt = null,
        public readonly ?string $endTime = null,
        public readonly ?string $endTimeLt = null,
        public readonly ?string $endTimeGt = null,
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
            'Status' => $this->status,
            'ParentCallSid' => $this->parentCallSid,
            'StartTime' => $this->startTime,
            'StartTime<' => $this->startTimeLt,
            'StartTime>' => $this->startTimeGt,
            'EndTime' => $this->endTime,
            'EndTime<' => $this->endTimeLt,
            'EndTime>' => $this->endTimeGt,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
