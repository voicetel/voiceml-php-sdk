<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListNotificationsParams;
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

    /**
     * @return array<string,mixed>
     */
    public function get(string $notificationSid): array
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Notifications', $notificationSid));
        return $raw;
    }

    /**
     * Generator that lazily walks all pages of `/Notifications`, yielding one item at a time.
     *
     * @return \Generator<int, mixed>
     */
    public function iterate(
        ?int $log = null,
        ?string $messageDate = null,
        ?string $messageDateLt = null,
        ?string $messageDateGt = null,
        ?int $pageSize = null,
    ): \Generator {
        $page = 0;
        while (true) {
            $chunk = $this->list(new ListNotificationsParams(
                page: $page,
                pageSize: $pageSize,
                log: $log,
                messageDate: $messageDate,
                messageDateLt: $messageDateLt,
                messageDateGt: $messageDateGt,
            ));
            foreach ($chunk->notifications as $notification) {
                yield $notification;
            }
            if ($chunk->notifications === []) {
                return;
            }
            $page++;
        }
    }
}
