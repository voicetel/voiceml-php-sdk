<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Conference;
use VoiceML\Model\ConferenceList;
use VoiceML\Model\EndConferenceRequest;
use VoiceML\Model\Participant;
use VoiceML\Model\ParticipantList;
use VoiceML\Model\RecordingList;
use VoiceML\Model\UpdateParticipantRequest;

/**
 * `/Conferences` and `/Conferences/{sid}/Participants`, `/Conferences/{sid}/Recordings`.
 */
final class ConferencesResource extends Resource
{
    public function list(): ConferenceList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences'));
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

    public function listParticipants(string $conferenceSid): ParticipantList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences', $conferenceSid, 'Participants'));
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

    // --- Recordings ---

    public function listRecordings(string $conferenceSid): RecordingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Conferences', $conferenceSid, 'Recordings'));
        return RecordingList::fromArray($raw);
    }
}
