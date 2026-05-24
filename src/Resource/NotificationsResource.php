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
}
