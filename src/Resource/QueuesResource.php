<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateQueueRequest;
use VoiceML\Model\DequeueRequest;
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

    public function list(): QueueList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues'));
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

    public function listMembers(string $queueSid): QueueMemberList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Queues', $queueSid, 'Members'));
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
}
