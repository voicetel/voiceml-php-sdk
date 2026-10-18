<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListPageParams;
use VoiceML\Model\MonitorAlert;
use VoiceML\Model\MonitorAlertList;

/**
 * `/v1/Alerts` — the monitor alert log.
 */
final class MonitorAlertsResource extends Resource
{
    /**
     * @param array<string,string|int> $query
     */
    public function list(array $query = []): MonitorAlertList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Alerts', $query);
        return MonitorAlertList::fromArray($raw);
    }

    public function fetch(string $sid): MonitorAlert
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/Alerts/' . rawurlencode($sid));
        return MonitorAlert::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `meta.next_page_url` points at.
     * Returns null on the last page.
     */
    public function nextPage(MonitorAlertList $page): ?MonitorAlertList
    {
        $url = $page->meta?->nextPageUrl;
        if ($url === null || $url === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($url);
        return MonitorAlertList::fromArray($raw);
    }

    /**
     * Generator that lazily walks all alert pages, following
     * `meta.next_page_url` verbatim.
     *
     * @param array<string,string|int> $query
     * @return \Generator<int, MonitorAlert>
     */
    public function iterate(array $query = []): \Generator
    {
        $page = $this->list($query);
        while (true) {
            foreach ($page->alerts as $alert) {
                yield $alert;
            }
            $page = $this->nextPage($page);
            if ($page === null) {
                return;
            }
        }
    }
}
