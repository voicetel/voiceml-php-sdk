<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateIncomingPhoneNumberRequest;
use VoiceML\Model\IncomingPhoneNumber;
use VoiceML\Model\IncomingPhoneNumberList;
use VoiceML\Model\UpdateIncomingPhoneNumberRequest;

/**
 * `/IncomingPhoneNumbers` — DIDs assigned to the authenticated tenant.
 *
 * Tenant-scoped: list/get/update/delete only see the caller's own rows; the global admin
 * surface is on the private listener and not exposed here. Numbers belonging to other
 * accounts 404 with the same shape as a nonexistent number (no enumeration leak).
 *
 * SID format note: the resource path uses the canonical `PN`-prefixed sid (e.g.
 * `PN0123456789abcdef0123456789abcdef`), **not** the E.164 number. Use `list()` with the
 * `$phoneNumber` filter to look up a sid from a number, then `get()`/`update()`/`delete()`
 * with the resulting sid.
 */
final class IncomingPhoneNumbersResource extends Resource
{
    public function list(
        ?string $phoneNumber = null,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $pageToken = null,
    ): IncomingPhoneNumberList {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('IncomingPhoneNumbers'),
            [
                'PhoneNumber' => $phoneNumber,
                'Page' => $page,
                'PageSize' => $pageSize,
                'PageToken' => $pageToken,
            ],
        );
        return IncomingPhoneNumberList::fromArray($raw);
    }

    public function create(CreateIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('IncomingPhoneNumbers'),
            null,
            $body->toForm(),
        );
        return IncomingPhoneNumber::fromArray($raw);
    }

    public function get(string $sid): IncomingPhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('IncomingPhoneNumbers', $sid));
        return IncomingPhoneNumber::fromArray($raw);
    }

    public function update(string $sid, UpdateIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('IncomingPhoneNumbers', $sid),
            null,
            $body->toForm(),
        );
        return IncomingPhoneNumber::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->path('IncomingPhoneNumbers', $sid));
    }
}
