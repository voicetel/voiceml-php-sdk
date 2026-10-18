<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateIncomingPhoneNumberRequest;
use VoiceML\Model\CreateTypedIncomingPhoneNumberRequest;
use VoiceML\Model\IncomingPhoneNumber;
use VoiceML\Model\IncomingPhoneNumberList;
use VoiceML\Model\ListIncomingPhoneNumbersParams;
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
    public function list(?ListIncomingPhoneNumbersParams $params = null): IncomingPhoneNumberList
    {
        $query = ($params ?? new ListIncomingPhoneNumbersParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('IncomingPhoneNumbers'), $query);
        return IncomingPhoneNumberList::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(IncomingPhoneNumberList $page): ?IncomingPhoneNumberList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
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

    public function listLocal(?ListIncomingPhoneNumbersParams $params = null): IncomingPhoneNumberList
    {
        return $this->listTyped('Local', $params);
    }

    public function createLocal(CreateTypedIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        return $this->createTyped('Local', $body);
    }

    public function listMobile(?ListIncomingPhoneNumbersParams $params = null): IncomingPhoneNumberList
    {
        return $this->listTyped('Mobile', $params);
    }

    public function createMobile(CreateTypedIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        return $this->createTyped('Mobile', $body);
    }

    public function listTollFree(?ListIncomingPhoneNumbersParams $params = null): IncomingPhoneNumberList
    {
        return $this->listTyped('TollFree', $params);
    }

    public function createTollFree(CreateTypedIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        return $this->createTyped('TollFree', $body);
    }

    private function listTyped(string $kind, ?ListIncomingPhoneNumbersParams $params): IncomingPhoneNumberList
    {
        $query = ($params ?? new ListIncomingPhoneNumbersParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('IncomingPhoneNumbers', $kind), $query);
        return IncomingPhoneNumberList::fromArray($raw);
    }

    private function createTyped(string $kind, CreateTypedIncomingPhoneNumberRequest $body): IncomingPhoneNumber
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('IncomingPhoneNumbers', $kind),
            null,
            $body->toForm(),
        );
        return IncomingPhoneNumber::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/IncomingPhoneNumbers`, yielding
     * one IncomingPhoneNumber at a time. Follows `next_page_uri` verbatim;
     * stopping the generator early makes no further requests.
     *
     * @return \Generator<int, IncomingPhoneNumber>
     */
    public function iterate(?ListIncomingPhoneNumbersParams $params = null): \Generator
    {
        $page = $this->list($params);
        while (true) {
            foreach ($page->incomingPhoneNumbers as $number) {
                yield $number;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->incomingPhoneNumbers === []) {
                return;
            }
            $next = $this->nextPage($page);
            if ($next === null) {
                return;
            }
            $page = $next;
        }
    }
}
