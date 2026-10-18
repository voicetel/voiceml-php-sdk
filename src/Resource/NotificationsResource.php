<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListNotificationsParams;
use VoiceML\Model\Notification;
use VoiceML\Model\NotificationsList;

/**
 * Account-scoped `/Notifications` compat stubs (always empty list; fetch returns 404).
 */
final class NotificationsResource extends Resource
{
    public function list(?ListNotificationsParams $params = null): NotificationsList
    {
        $query = ($params ?? new ListNotificationsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Notifications'), $query);
        return NotificationsList::fromArray($raw);
    }

    public function get(string $notificationSid): Notification
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Notifications', $notificationSid));
        return Notification::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/Notifications`, yielding one
     * Notification at a time. Follows `next_page_uri` verbatim; stopping the
     * generator early makes no further requests.
     *
     * @return \Generator<int, Notification>
     */
    public function iterate(
        ?int $log = null,
        ?string $messageDate = null,
        ?string $messageDateLt = null,
        ?string $messageDateGt = null,
        ?int $pageSize = null,
    ): \Generator {
        $page = $this->list(new ListNotificationsParams(
            pageSize: $pageSize,
            log: $log,
            messageDate: $messageDate,
            messageDateLt: $messageDateLt,
            messageDateGt: $messageDateGt,
        ));
        while (true) {
            foreach ($page->notifications as $notification) {
                yield $notification;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->notifications === []) {
                return;
            }
            $next = $this->nextPage($page);
            if ($next === null) {
                return;
            }
            $page = $next;
        }
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(NotificationsList $page): ?NotificationsList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return NotificationsList::fromArray($raw);
    }
}
