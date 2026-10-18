<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListUsageRecordsParams;
use VoiceML\Model\UsageRecordList;

/**
 * `/Usage/Records` — usage rollups for the account, plus the eight window
 * variants (`AllTime`, `Daily`, `LastMonth`, `Monthly`, `ThisMonth`, `Today`,
 * `Yearly`, `Yesterday`).
 */
final class UsageRecordsResource extends Resource
{
    public function list(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow(null, $params);
    }

    public function listAllTime(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('AllTime', $params);
    }

    public function listDaily(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('Daily', $params);
    }

    public function listLastMonth(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('LastMonth', $params);
    }

    public function listMonthly(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('Monthly', $params);
    }

    public function listThisMonth(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('ThisMonth', $params);
    }

    public function listToday(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('Today', $params);
    }

    public function listYearly(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('Yearly', $params);
    }

    public function listYesterday(?ListUsageRecordsParams $params = null): UsageRecordList
    {
        return $this->listWindow('Yesterday', $params);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(UsageRecordList $page): ?UsageRecordList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return UsageRecordList::fromArray($raw);
    }

    private function listWindow(?string $window, ?ListUsageRecordsParams $params): UsageRecordList
    {
        $segments = ['Usage', 'Records'];
        if ($window !== null) {
            $segments[] = $window;
        }
        $query = ($params ?? new ListUsageRecordsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path(...$segments), $query);
        return UsageRecordList::fromArray($raw);
    }
}
