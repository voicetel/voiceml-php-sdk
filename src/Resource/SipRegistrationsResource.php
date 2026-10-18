<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListPageParams;
use VoiceML\Model\SipRegistration;
use VoiceML\Model\SipRegistrationList;

/**
 * `/SIP/Registrations` — live SIP endpoint registrations across the
 * account's domains.
 */
final class SipRegistrationsResource extends Resource
{
    public function list(?ListPageParams $params = null): SipRegistrationList
    {
        $query = ($params ?? new ListPageParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Registrations'), $query);
        return SipRegistrationList::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(SipRegistrationList $page): ?SipRegistrationList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return SipRegistrationList::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/SIP/Registrations`.
     * Follows `next_page_uri` verbatim.
     *
     * @return \Generator<int, SipRegistration>
     */
    public function iterate(?int $pageSize = null): \Generator
    {
        $page = $this->list(new ListPageParams(pageSize: $pageSize));
        while (true) {
            foreach ($page->registrations as $registration) {
                yield $registration;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->registrations === []) {
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
