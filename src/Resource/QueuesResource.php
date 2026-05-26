<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateQueueRequest;
use VoiceML\Model\DequeueRequest;
use VoiceML\Model\ListPageParams;
use VoiceML\Model\Queue;
use VoiceML\Model\QueueList;
use VoiceML\Model\QueueMember;
use VoiceML\Model\QueueMemberList;
use VoiceML\Model\UpdateQueueRequest;

/**
 * `/Queues` and `/Queues/{sid}/Members`.
 */
final class QueuesResource extends Resource
{
    public function create(CreateQueueRequest $body): Queue
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Queues'), null, $body->toForm());
        return Queue::fromArray($raw);
    }

    public function list(?ListPageParams $params = null): QueueList
    {
        $query = ($params ?? new ListPageParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues'), $query);
        return QueueList::fromArray($raw);
    }

    public function get(string $queueSid): Queue
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues', $queueSid));
        return Queue::fromArray($raw);
    }

    public function update(string $queueSid, UpdateQueueRequest $body): Queue
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Queues', $queueSid), null, $body->toForm());
        return Queue::fromArray($raw);
    }

    public function delete(string $queueSid): void
    {
        $this->transport->request('DELETE', $this->path('Queues', $queueSid));
    }

    // --- Members ---

    public function listMembers(string $queueSid, ?ListPageParams $params = null): QueueMemberList
    {
        $query = ($params ?? new ListPageParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues', $queueSid, 'Members'), $query);
        return QueueMemberList::fromArray($raw);
    }

    public function peekFront(string $queueSid): QueueMember
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues', $queueSid, 'Members', 'Front'));
        return QueueMember::fromArray($raw);
    }

    public function dequeueFront(string $queueSid, DequeueRequest $body): QueueMember
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Queues', $queueSid, 'Members', 'Front'),
            null,
            $body->toForm(),
        );
        return QueueMember::fromArray($raw);
    }

    public function getMember(string $queueSid, string $callSid): QueueMember
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues', $queueSid, 'Members', $callSid));
        return QueueMember::fromArray($raw);
    }

    public function dequeueMember(string $queueSid, string $callSid, DequeueRequest $body): QueueMember
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Queues', $queueSid, 'Members', $callSid),
            null,
            $body->toForm(),
        );
        return QueueMember::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/Queues`, yielding one Queue at a time.
     *
     * @return \Generator<int, Queue>
     */
    public function iterate(?int $pageSize = null): \Generator
    {
        $page = 0;
        while (true) {
            $chunk = $this->list(new ListPageParams(
                page: $page,
                pageSize: $pageSize,
            ));
            foreach ($chunk->queues as $queue) {
                yield $queue;
            }
            if (($chunk->nextPageUri ?? null) === null || $chunk->queues === []) {
                return;
            }
            $page++;
        }
    }
}
