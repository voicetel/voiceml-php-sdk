<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Conference;
use VoiceML\Model\ConferenceList;
use VoiceML\Model\CreateParticipantRequest;
use VoiceML\Model\EndConferenceRequest;
use VoiceML\Model\ListCallRecordingsParams;
use VoiceML\Model\ListConferencesParams;
use VoiceML\Model\ListParticipantsParams;
use VoiceML\Model\Participant;
use VoiceML\Model\ParticipantList;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingList;
use VoiceML\Model\StartConferenceRecordingRequest;
use VoiceML\Model\UpdateParticipantRequest;
use VoiceML\Model\UpdateConferenceRecordingRequest;

/**
 * `/Conferences` and `/Conferences/{sid}/Participants`, `/Conferences/{sid}/Recordings`.
 */
final class ConferencesResource extends Resource
{
    public function list(?ListConferencesParams $params = null): ConferenceList
    {
        $query = ($params ?? new ListConferencesParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences'), $query);
        return ConferenceList::fromArray($raw);
    }

    public function get(string $conferenceSid): Conference
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences', $conferenceSid));
        return Conference::fromArray($raw);
    }

    public function end(string $conferenceSid, ?EndConferenceRequest $body = null): Conference
    {
        $payload = ($body ?? new EndConferenceRequest())->toForm();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Conferences', $conferenceSid), null, $payload);
        return Conference::fromArray($raw);
    }

    // --- Participants ---

    public function listParticipants(string $conferenceSid, ?ListParticipantsParams $params = null): ParticipantList
    {
        $query = ($params ?? new ListParticipantsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Conferences', $conferenceSid, 'Participants'),
            $query,
        );
        return ParticipantList::fromArray($raw);
    }

    public function getParticipant(string $conferenceSid, string $callSid): Participant
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Conferences', $conferenceSid, 'Participants', $callSid),
        );
        return Participant::fromArray($raw);
    }

    public function updateParticipant(
        string $conferenceSid,
        string $callSid,
        UpdateParticipantRequest $body,
    ): Participant {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Conferences', $conferenceSid, 'Participants', $callSid),
            null,
            $body->toForm(),
        );
        return Participant::fromArray($raw);
    }

    public function kickParticipant(string $conferenceSid, string $callSid): void
    {
        $this->transport->request(
            'DELETE',
            $this->path('Conferences', $conferenceSid, 'Participants', $callSid),
        );
    }

    public function createParticipant(string $conferenceSid, CreateParticipantRequest $body): Participant
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Conferences', $conferenceSid, 'Participants'),
            null,
            $body->toForm(),
        );
        return Participant::fromArray($raw);
    }

    // --- Recordings ---

    public function listRecordings(string $conferenceSid, ?ListCallRecordingsParams $params = null): RecordingList
    {
        $query = ($params ?? new ListCallRecordingsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences', $conferenceSid, 'Recordings'), $query);
        return RecordingList::fromArray($raw);
    }

    public function getRecording(string $conferenceSid, string $recordingSid): Recording
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Conferences', $conferenceSid, 'Recordings', $recordingSid),
        );
        return Recording::fromArray($raw);
    }

    public function updateRecording(
        string $conferenceSid,
        string $recordingSid,
        UpdateConferenceRecordingRequest $body,
    ): Recording {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Conferences', $conferenceSid, 'Recordings', $recordingSid),
            null,
            $body->toForm(),
        );
        return Recording::fromArray($raw);
    }

    public function deleteRecording(string $conferenceSid, string $recordingSid): void
    {
        $this->transport->request(
            'DELETE',
            $this->path('Conferences', $conferenceSid, 'Recordings', $recordingSid),
        );
    }

    /**
     * Start recording a conference (`POST /Conferences/{sid}/Recordings`).
     */
    public function startRecording(
        string $conferenceSid,
        ?StartConferenceRecordingRequest $body = null,
    ): Recording {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'POST',
            $this->path('Conferences', $conferenceSid, 'Recordings'),
            null,
            $body !== null ? $body->toForm() : null,
        );
        return Recording::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(ConferenceList $page): ?ConferenceList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return ConferenceList::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/Conferences`, yielding one
     * Conference at a time. Follows `next_page_uri` verbatim; stopping the
     * generator early makes no further requests.
     *
     * @return \Generator<int, Conference>
     */
    public function iterate(?ListConferencesParams $params = null): \Generator
    {
        $page = $this->list($params);
        while (true) {
            foreach ($page->conferences as $conference) {
                yield $conference;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->conferences === []) {
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
