<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Call;
use VoiceML\Model\CallList;
use VoiceML\Model\CallTranscription;
use VoiceML\Model\CreateCallRequest;
use VoiceML\Model\EventsList;
use VoiceML\Model\NotificationsList;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingList;
use VoiceML\Model\SiprecList;
use VoiceML\Model\SiprecSession;
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
        ?string $startTimeGte = null,
        ?string $startTimeLte = null,
        ?int $page = null,
        ?int $pageSize = null,
    ): CallList {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Calls'),
            self::listParams($to, $from, $status, $parentCallSid, $startTimeGte, $startTimeLte, $page, $pageSize),
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

    public function listRecordings(string $callSid): RecordingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Recordings'));
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

    // --- Notifications / Events (compat stubs) ---

    public function listNotifications(string $callSid): NotificationsList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Notifications'));
        return NotificationsList::fromArray($raw);
    }

    public function listEvents(string $callSid): EventsList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Calls', $callSid, 'Events'));
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
     * Walk all pages of `/Calls` and return a list. Use for small-to-medium result sets;
     * for very large pulls, iterate `list()->nextPageUri` manually.
     *
     * @return list<Call>
     */
    public function iterate(
        ?string $to = null,
        ?string $from = null,
        ?string $status = null,
        ?string $parentCallSid = null,
        ?string $startTimeGte = null,
        ?string $startTimeLte = null,
        ?int $pageSize = null,
    ): array {
        /** @var list<Call> $out */
        $out = [];
        $page = 0;
        while (true) {
            $chunk = $this->list(
                to: $to,
                from: $from,
                status: $status,
                parentCallSid: $parentCallSid,
                startTimeGte: $startTimeGte,
                startTimeLte: $startTimeLte,
                page: $page,
                pageSize: $pageSize,
            );
            foreach ($chunk->calls as $call) {
                $out[] = $call;
            }
            if (($chunk->nextPageUri ?? null) === null || $chunk->calls === []) {
                return $out;
            }
            $page += 1;
        }
    }

    /**
     * @return array<string,mixed>
     */
    private static function listParams(
        ?string $to,
        ?string $from,
        ?string $status,
        ?string $parentCallSid,
        ?string $startTimeGte,
        ?string $startTimeLte,
        ?int $page,
        ?int $pageSize,
    ): array {
        // Note: spec defines `StartTime>=` and `StartTime<=` as the literal query names.
        return [
            'To' => $to,
            'From' => $from,
            'Status' => $status,
            'ParentCallSid' => $parentCallSid,
            'StartTime>=' => $startTimeGte,
            'StartTime<=' => $startTimeLte,
            'Page' => $page,
            'PageSize' => $pageSize,
        ];
    }
}
