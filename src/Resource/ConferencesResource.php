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
use VoiceML\Model\UpdateParticipantRequest;
use VoiceML\Model\UpdateRecordingRequest;

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
        UpdateRecordingRequest $body,
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
     * Generator that lazily walks all pages of `/Conferences`, yielding one Conference at a time.
     *
     * @return \Generator<int, Conference>
     */
    public function iterate(
        ?string $friendlyName = null,
        ?string $status = null,
        ?string $dateCreated = null,
        ?string $dateCreatedLt = null,
        ?string $dateCreatedGt = null,
        ?string $dateUpdated = null,
        ?string $dateUpdatedLt = null,
        ?string $dateUpdatedGt = null,
        ?int $pageSize = null,
    ): \Generator {
        $page = 0;
        while (true) {
            $chunk = $this->list(new ListConferencesParams(
                friendlyName: $friendlyName,
                status: $status,
                dateCreated: $dateCreated,
                dateCreatedLt: $dateCreatedLt,
                dateCreatedGt: $dateCreatedGt,
                dateUpdated: $dateUpdated,
                dateUpdatedLt: $dateUpdatedLt,
                dateUpdatedGt: $dateUpdatedGt,
                page: $page,
                pageSize: $pageSize,
            ));
            foreach ($chunk->conferences as $conference) {
                yield $conference;
            }
            if (($chunk->nextPageUri ?? null) === null || $chunk->conferences === []) {
                return;
            }
            $page++;
        }
    }
}
