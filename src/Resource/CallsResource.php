<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Call;
use VoiceML\Model\CallList;
use VoiceML\Model\CallPayment;
use VoiceML\Model\CallTranscription;
use VoiceML\Model\CreateCallRequest;
use VoiceML\Model\EventsList;
use VoiceML\Model\ListCallsParams;
use VoiceML\Model\ListNotificationsParams;
use VoiceML\Model\ListPageParams;
use VoiceML\Model\ListRecordingsParams;
use VoiceML\Model\NotificationsList;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingList;
use VoiceML\Model\SiprecList;
use VoiceML\Model\SiprecSession;
use VoiceML\Model\StartPaymentRequest;
use VoiceML\Model\StartRecordingRequest;
use VoiceML\Model\StartSiprecRequest;
use VoiceML\Model\StartStreamRequest;
use VoiceML\Model\StartTranscriptionRequest;
use VoiceML\Model\StopSiprecRequest;
use VoiceML\Model\StopStreamRequest;
use VoiceML\Model\StopTranscriptionRequest;
use VoiceML\Model\Stream;
use VoiceML\Model\StreamList;
use VoiceML\Model\TranscriptionList;
use VoiceML\Model\UpdateCallRequest;
use VoiceML\Model\UpdatePaymentRequest;
use VoiceML\Model\UpdateRecordingRequest;

/**
 * `/Calls` and call-scoped sub-resources (Recordings, Streams, Siprec, Transcriptions,
 * Notifications, Events, UserDefinedMessages).
 */
final class CallsResource extends Resource
{
    public function list(
        ?string $to = null,
        ?string $from = null,
        ?string $status = null,
        ?string $parentCallSid = null,
        ?string $startTime = null,
        ?string $startTimeLt = null,
        ?string $startTimeGt = null,
        ?string $startTimeGte = null,
        ?string $startTimeLte = null,
        ?string $endTime = null,
        ?string $endTimeLt = null,
        ?string $endTimeGt = null,
        ?int $page = null,
        ?int $pageSize = null,
        ?string $pageToken = null,
    ): CallList {
        $params = new ListCallsParams(
            to: $to,
            from: $from,
            status: $status,
            parentCallSid: $parentCallSid,
            startTime: $startTime,
            startTimeLt: $startTimeLt,
            startTimeGt: $startTimeGt,
            startTimeGte: $startTimeGte,
            startTimeLte: $startTimeLte,
            endTime: $endTime,
            endTimeLt: $endTimeLt,
            endTimeGt: $endTimeGt,
            page: $page,
            pageSize: $pageSize,
            pageToken: $pageToken,
        );
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Calls'),
            $params->toQuery(),
        );
        return CallList::fromArray($raw);
    }

    public function create(CreateCallRequest $body): Call
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Calls'), null, $body->toForm());
        return Call::fromArray($raw);
    }

    public function get(string $callSid): Call
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid));
        return Call::fromArray($raw);
    }

    public function update(string $callSid, UpdateCallRequest $body): Call
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Calls', $callSid), null, $body->toForm());
        return Call::fromArray($raw);
    }

    public function delete(string $callSid): void
    {
        $this->transport->request('DELETE', $this->path('Calls', $callSid));
    }

    // --- Recordings (call-scoped) ---

    public function listRecordings(string $callSid, ?ListRecordingsParams $params = null): RecordingList
    {
        $query = ($params ?? new ListRecordingsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Recordings'), $query);
        return RecordingList::fromArray($raw);
    }

    public function startRecording(string $callSid, ?StartRecordingRequest $body = null): Recording
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Recordings'),
            null,
            $body !== null ? $body->toForm() : null,
        );
        return Recording::fromArray($raw);
    }

    public function getRecording(string $callSid, string $recordingSid): Recording
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Recordings', $recordingSid));
        return Recording::fromArray($raw);
    }

    public function updateRecording(string $callSid, string $recordingSid, UpdateRecordingRequest $body): Recording
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Recordings', $recordingSid),
            null,
            $body->toForm(),
        );
        return Recording::fromArray($raw);
    }

    public function deleteRecording(string $callSid, string $recordingSid): void
    {
        $this->transport->request('DELETE', $this->path('Calls', $callSid, 'Recordings', $recordingSid));
    }

    // --- Streams ---

    public function listStreams(string $callSid): StreamList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Streams'));
        return StreamList::fromArray($raw);
    }

    public function startStream(string $callSid, StartStreamRequest $body): Stream
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Streams'),
            null,
            $body->toForm(),
        );
        return Stream::fromArray($raw);
    }

    public function getStream(string $callSid, string $streamSid): Stream
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Streams', $streamSid));
        return Stream::fromArray($raw);
    }

    public function stopStream(string $callSid, string $streamSid, ?StopStreamRequest $body = null): Stream
    {
        $payload = ($body ?? new StopStreamRequest())->toForm();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Streams', $streamSid),
            null,
            $payload,
        );
        return Stream::fromArray($raw);
    }

    // --- SIPREC ---

    public function listSiprec(string $callSid): SiprecList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Siprec'));
        return SiprecList::fromArray($raw);
    }

    public function startSiprec(string $callSid, ?StartSiprecRequest $body = null): SiprecSession
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Siprec'),
            null,
            $body !== null ? $body->toForm() : null,
        );
        return SiprecSession::fromArray($raw);
    }

    public function getSiprec(string $callSid, string $siprecSid): SiprecSession
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Siprec', $siprecSid));
        return SiprecSession::fromArray($raw);
    }

    public function stopSiprec(string $callSid, string $siprecSid, ?StopSiprecRequest $body = null): SiprecSession
    {
        $payload = ($body ?? new StopSiprecRequest())->toForm();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Siprec', $siprecSid),
            null,
            $payload,
        );
        return SiprecSession::fromArray($raw);
    }

    // --- Transcriptions ---

    public function listTranscriptions(string $callSid): TranscriptionList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Transcriptions'));
        return TranscriptionList::fromArray($raw);
    }

    public function startTranscription(string $callSid, ?StartTranscriptionRequest $body = null): CallTranscription
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Transcriptions'),
            null,
            $body !== null ? $body->toForm() : null,
        );
        return CallTranscription::fromArray($raw);
    }

    public function getTranscription(string $callSid, string $transcriptionSid): CallTranscription
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Transcriptions', $transcriptionSid));
        return CallTranscription::fromArray($raw);
    }

    public function stopTranscription(
        string $callSid,
        string $transcriptionSid,
        ?StopTranscriptionRequest $body = null,
    ): CallTranscription {
        $payload = ($body ?? new StopTranscriptionRequest())->toForm();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Transcriptions', $transcriptionSid),
            null,
            $payload,
        );
        return CallTranscription::fromArray($raw);
    }

    // --- Payments (`<Pay>` REST companion) ---

    /**
     * Begin a `<Pay>` session on the live call. Returns the freshly-minted {@see CallPayment}.
     *
     * Raises {@see \VoiceML\Exception\PermissionDeniedException} (HTTP 403) when the tenant is
     * not `pay_enabled` or has no `stripe_secret_key` configured.
     */
    public function startPayment(string $callSid, StartPaymentRequest $body): CallPayment
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Payments'),
            null,
            $body->toForm(),
        );
        return CallPayment::fromArray($raw);
    }

    /**
     * Advance or terminate an existing Pay session.
     *
     * `Status=complete` captures the collected fields; `Status=cancel` aborts the session.
     * `Capture=...` tells the runtime which input the user is about to type next.
     */
    public function updatePayment(
        string $callSid,
        string $paymentSid,
        UpdatePaymentRequest $body,
    ): CallPayment {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'Payments', $paymentSid),
            null,
            $body->toForm(),
        );
        return CallPayment::fromArray($raw);
    }

    // --- Notifications / Events (compat stubs) ---

    public function listNotifications(string $callSid, ?ListNotificationsParams $params = null): NotificationsList
    {
        $query = ($params ?? new ListNotificationsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Notifications'), $query);
        return NotificationsList::fromArray($raw);
    }

    /**
     * @return array<string,mixed>
     */
    public function getNotification(string $callSid, string $notificationSid): array
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Calls', $callSid, 'Notifications', $notificationSid),
        );
        return $raw;
    }

    public function listEvents(string $callSid, ?ListPageParams $params = null): EventsList
    {
        $query = ($params ?? new ListPageParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Events'), $query);
        return EventsList::fromArray($raw);
    }

    /**
     * `POST /Calls/{sid}/UserDefinedMessages` — always raises
     * {@see \VoiceML\Exception\NotImplementedApiException}.
     *
     * Mounted on the server only as a 501 stub. The SDK forwards the call so callers get a
     * clean exception rather than discovering at runtime that the endpoint doesn't exist.
     *
     * @param array<string,mixed>|null $payload
     */
    public function sendUserDefinedMessage(string $callSid, ?array $payload = null): void
    {
        $this->transport->request(
            'POST',
            $this->path('Calls', $callSid, 'UserDefinedMessages'),
            null,
            $payload,
        );
    }

    /**
     * Generator that lazily walks all pages of `/Calls`, yielding one Call at a time.
     *
     * @return \Generator<int, Call>
     */
    public function iterate(
        ?string $to = null,
        ?string $from = null,
        ?string $status = null,
        ?string $parentCallSid = null,
        ?string $startTime = null,
        ?string $startTimeLt = null,
        ?string $startTimeGt = null,
        ?string $startTimeGte = null,
        ?string $startTimeLte = null,
        ?string $endTime = null,
        ?string $endTimeLt = null,
        ?string $endTimeGt = null,
        ?int $pageSize = null,
    ): \Generator {
        $page = 0;
        while (true) {
            $chunk = $this->list(
                to: $to,
                from: $from,
                status: $status,
                parentCallSid: $parentCallSid,
                startTime: $startTime,
                startTimeLt: $startTimeLt,
                startTimeGt: $startTimeGt,
                startTimeGte: $startTimeGte,
                startTimeLte: $startTimeLte,
                endTime: $endTime,
                endTimeLt: $endTimeLt,
                endTimeGt: $endTimeGt,
                page: $page,
                pageSize: $pageSize,
            );
            foreach ($chunk->calls as $call) {
                yield $call;
            }
            if (($chunk->nextPageUri ?? null) === null || $chunk->calls === []) {
                return;
            }
            $page++;
        }
    }
}
