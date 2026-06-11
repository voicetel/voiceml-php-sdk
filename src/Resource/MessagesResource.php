<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateMessageRequest;
use VoiceML\Model\ListMessagesParams;
use VoiceML\Model\Message;
use VoiceML\Model\MessageList;
use VoiceML\Model\UpdateMessageRequest;

/**
 * `/Messages` — the Twilio-compatible SMS surface, backed by the SDK 2.2 gateway.
 *
 * Outbound-only today (no MMS, no inbound webhook delivery). The wire surface
 * accepts and persists every Twilio-documented field; runtime behaviour is
 * fire-and-forget, so `status` lands at `sent` or `failed` immediately and the
 * `canceled` lifecycle returns 21610.
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
     * Generator that lazily walks all pages of `/Messages`, yielding one Message at a time.
     *
     * @return \Generator<int, Message>
     */
    public function iterate(
        ?string $to = null,
        ?string $from = null,
        ?string $dateSent = null,
        ?string $dateSentLt = null,
        ?string $dateSentGt = null,
        ?int $pageSize = null,
    ): \Generator {
        $page = 0;
        while (true) {
            $chunk = $this->list(new ListMessagesParams(
                to: $to,
                from: $from,
                dateSent: $dateSent,
                dateSentLt: $dateSentLt,
                dateSentGt: $dateSentGt,
                page: $page,
                pageSize: $pageSize,
            ));
            foreach ($chunk->messages as $message) {
                yield $message;
            }
            if (($chunk->nextPageUri ?? null) === null || $chunk->messages === []) {
                return;
            }
            $page++;
        }
    }
}
