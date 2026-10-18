<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateOutgoingCallerIdRequest;
use VoiceML\Model\OutgoingCallerId;
use VoiceML\Model\OutgoingCallerIdList;
use VoiceML\Model\UpdateOutgoingCallerIdRequest;
use VoiceML\Model\ValidationRequest;

/**
 * `/OutgoingCallerIds` — verified caller IDs for outbound dialing.
 *
 * `create` triggers a validation call: the API dials the number and the
 * human answers with the returned `validationCode` before the caller ID
 * becomes usable.
 */
final class OutgoingCallerIdsResource extends Resource
{
    public function list(
        ?string $phoneNumber = null,
        ?string $friendlyName = null,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $pageToken = null,
    ): OutgoingCallerIdList {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('OutgoingCallerIds'),
            [
                'PhoneNumber' => $phoneNumber,
                'FriendlyName' => $friendlyName,
                'Page' => $page,
                'PageSize' => $pageSize,
                'PageToken' => $pageToken,
            ],
        );
        return OutgoingCallerIdList::fromArray($raw);
    }

    public function create(CreateOutgoingCallerIdRequest $body): ValidationRequest
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('OutgoingCallerIds'),
            null,
            $body->toForm(),
        );
        return ValidationRequest::fromArray($raw);
    }

    public function fetch(string $sid): OutgoingCallerId
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('OutgoingCallerIds', $sid));
        return OutgoingCallerId::fromArray($raw);
    }

    public function update(string $sid, UpdateOutgoingCallerIdRequest $body): OutgoingCallerId
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('OutgoingCallerIds', $sid),
            null,
            $body->toForm(),
        );
        return OutgoingCallerId::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->path('OutgoingCallerIds', $sid));
    }

    /**
     * Generator that lazily walks all pages of `/OutgoingCallerIds`.
     * Follows `next_page_uri` verbatim.
     *
     * @return \Generator<int, OutgoingCallerId>
     */
    public function iterate(?int $pageSize = null): \Generator
    {
        $page = $this->list(pageSize: $pageSize);
        while (true) {
            foreach ($page->outgoingCallerIds as $callerId) {
                yield $callerId;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->outgoingCallerIds === []) {
                return;
            }
            /** @var array<string,mixed> $raw */
            $raw = $this->transport->requestPageUri($page->nextPageUri);
            $page = OutgoingCallerIdList::fromArray($raw);
        }
    }
}
