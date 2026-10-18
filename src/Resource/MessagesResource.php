<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateMessageFeedbackRequest;
use VoiceML\Model\CreateMessageRequest;
use VoiceML\Model\ListCallRecordingsParams;
use VoiceML\Model\ListMessagesParams;
use VoiceML\Model\Message;
use VoiceML\Model\MessageFeedback;
use VoiceML\Model\MessageList;
use VoiceML\Model\MessageMedia;
use VoiceML\Model\MessageMediaList;
use VoiceML\Model\UpdateMessageRequest;

/**
 * `/Messages` — the VoiceML-compatible SMS/MMS surface, and the media
 * attached to them (`/Messages/{sid}/Media`).
 */
final class MessagesResource extends Resource
{
    public function create(CreateMessageRequest $body): Message
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Messages'), null, $body->toForm());
        return Message::fromArray($raw);
    }

    public function fetch(string $messageSid): Message
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Messages', $messageSid));
        return Message::fromArray($raw);
    }

    public function list(?ListMessagesParams $params = null): MessageList
    {
        $query = ($params ?? new ListMessagesParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Messages'), $query);
        return MessageList::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(MessageList $page): ?MessageList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return MessageList::fromArray($raw);
    }

    public function update(string $messageSid, UpdateMessageRequest $body): Message
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Messages', $messageSid),
            null,
            $body->toForm(),
        );
        return Message::fromArray($raw);
    }

    public function delete(string $messageSid): void
    {
        $this->transport->request('DELETE', $this->path('Messages', $messageSid));
    }

    /**
     * `POST /Messages/{Sid}/Feedback` — report a delivery outcome
     * (confirmed / unconfirmed) back to the API.
     */
    public function createFeedback(string $messageSid, CreateMessageFeedbackRequest $body): MessageFeedback
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Messages', $messageSid, 'Feedback'),
            null,
            $body->toForm(),
        );
        return MessageFeedback::fromArray($raw);
    }

    // --- Media (`/Messages/{sid}/Media`) ---

    public function listMedia(string $messageSid, ?ListCallRecordingsParams $params = null): MessageMediaList
    {
        // Media lists share the DateCreated-filter + pagination param shape.
        $query = ($params ?? new ListCallRecordingsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Messages', $messageSid, 'Media'), $query);
        return MessageMediaList::fromArray($raw);
    }

    /**
     * Fetch a media resource's metadata. The server answers 302 for the
     * binary itself; use {@see fetchMediaContent()} for the bytes.
     */
    public function getMedia(string $messageSid, string $sid): MessageMedia
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Messages', $messageSid, 'Media', $sid));
        return MessageMedia::fromArray($raw);
    }

    /**
     * Download a media attachment's bytes, following the 302 redirect with the
     * documented redirect-safety rules (auth stripped cross-host, no https→http).
     *
     * @return array{status:int, body:string, headers:array<string,array<int,string>>}
     */
    public function fetchMediaContent(string $messageSid, string $sid): array
    {
        return $this->transport->fetchBytes($this->path('Messages', $messageSid, 'Media', $sid), '*/*');
    }

    public function deleteMedia(string $messageSid, string $sid): void
    {
        $this->transport->request('DELETE', $this->path('Messages', $messageSid, 'Media', $sid));
    }

    /**
     * Generator that lazily walks all pages of `/Messages`, yielding one
     * Message at a time. Follows `next_page_uri` verbatim; stopping the
     * generator early makes no further requests.
     *
     * @return \Generator<int, Message>
     */
    public function iterate(?ListMessagesParams $params = null): \Generator
    {
        $page = $this->list($params);
        while (true) {
            foreach ($page->messages as $message) {
                yield $message;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->messages === []) {
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
