<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\GetRecordingParams;
use VoiceML\Model\ListRecordingsParams;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingAudio;
use VoiceML\Model\RecordingList;
use VoiceML\Model\Transcription;
use VoiceML\Model\TranscriptionList;
use VoiceML\Model\ListTranscriptionsParams;

/**
 * Account-scoped `/Recordings` operations, plus the transcriptions attached
 * to a recording (`/Recordings/{sid}/Transcriptions`).
 *
 * Per-call recording start/stop/list lives on {@see CallsResource} — this
 * resource handles the account-wide list, single-recording fetch (both
 * metadata and audio), delete, and recording-scoped transcriptions.
 */
final class RecordingsResource extends Resource
{
    public function list(?ListRecordingsParams $params = null): RecordingList
    {
        $query = ($params ?? new ListRecordingsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Recordings'), $query);
        return RecordingList::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(RecordingList $page): ?RecordingList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return RecordingList::fromArray($raw);
    }

    public function get(string $recordingSid, ?GetRecordingParams $params = null): Recording
    {
        $query = $params !== null ? $params->toQuery() : null;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Recordings', $recordingSid), $query);
        return Recording::fromArray($raw);
    }

    /**
     * Fetch the WAV audio for a recording.
     *
     * Three server delivery shapes are flattened into one result by following
     * the 302 redirect to S3 manually (Authorization is dropped as soon as the
     * host changes; an https→http downgrade is refused):
     *   * `200 OK` — local file present.
     *   * `302 Found` — archived to S3; the SDK follows the presigned URL.
     *   * `410 Gone` — local file gone AND no S3 key. Raises {@see \VoiceML\Exception\GoneException}.
     */
    public function getAudio(string $recordingSid): RecordingAudio
    {
        $result = $this->transport->fetchBytes($this->pathRaw('Recordings', $recordingSid) . '.wav');

        $headers = $result['headers'];
        $contentType = 'application/octet-stream';
        foreach ($headers as $name => $values) {
            if (strcasecmp((string) $name, 'Content-Type') === 0 && $values !== []) {
                $contentType = (string) $values[0];
                break;
            }
        }
        $viaRedirect = false;
        foreach ($headers as $name => $_) {
            if (strcasecmp((string) $name, 'x-amz-id-2') === 0) {
                $viaRedirect = $result['status'] === 200;
                break;
            }
        }

        return new RecordingAudio(
            sid: $recordingSid,
            content: $result['body'],
            contentType: $contentType,
            viaRedirect: $viaRedirect,
        );
    }

    public function delete(string $recordingSid): void
    {
        $this->transport->request('DELETE', $this->path('Recordings', $recordingSid));
    }

    // --- Recording-scoped transcriptions ---

    public function listTranscriptions(
        string $recordingSid,
        ?ListTranscriptionsParams $params = null,
    ): TranscriptionList {
        $query = ($params ?? new ListTranscriptionsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Recordings', $recordingSid, 'Transcriptions'),
            $query,
        );
        return TranscriptionList::fromArray($raw);
    }

    public function getTranscription(string $recordingSid, string $sid): Transcription
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            $this->path('Recordings', $recordingSid, 'Transcriptions', $sid),
        );
        return Transcription::fromArray($raw);
    }

    public function deleteTranscription(string $recordingSid, string $sid): void
    {
        $this->transport->request(
            'DELETE',
            $this->path('Recordings', $recordingSid, 'Transcriptions', $sid),
        );
    }

    /**
     * Generator that lazily walks all pages of `/Recordings`, yielding one
     * Recording at a time. Follows `next_page_uri` verbatim; stopping the
     * generator early makes no further requests.
     *
     * @return \Generator<int, Recording>
     */
    public function iterate(?ListRecordingsParams $params = null): \Generator
    {
        $page = $this->list($params);
        while (true) {
            foreach ($page->recordings as $recording) {
                yield $recording;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->recordings === []) {
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
