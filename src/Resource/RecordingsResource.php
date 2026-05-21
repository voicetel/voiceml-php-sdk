<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListRecordingsParams;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingAudio;
use VoiceML\Model\RecordingList;

/**
 * Account-scoped `/Recordings` operations.
 *
 * Per-call recording start/stop/list lives on {@see CallsResource} — this resource handles
 * the account-wide list, single-recording fetch (both metadata and audio), and delete.
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

    public function get(string $recordingSid): Recording
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Recordings', $recordingSid));
        return Recording::fromArray($raw);
    }

    /**
     * Fetch the WAV audio for a recording.
     *
     * Three server delivery shapes are flattened into one result by following any 302
     * redirect to S3:
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
}
