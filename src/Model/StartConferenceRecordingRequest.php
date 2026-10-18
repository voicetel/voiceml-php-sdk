<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Conferences/{sid}/Recordings` — the conference-scoped
 * recording-start surface. Only the recording status-callback fields are
 * accepted here (unlike the call-scoped {@see StartRecordingRequest}).
 */
final class StartConferenceRecordingRequest extends FormRequest
{
    /**
     * @param list<string>|null $recordingStatusCallbackEvent
     */
    public function __construct(
        public readonly ?string $recordingStatusCallback = null,
        public readonly ?string $recordingStatusCallbackMethod = null,
        public readonly ?array $recordingStatusCallbackEvent = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'RecordingStatusCallback' => 'recordingStatusCallback',
            'RecordingStatusCallbackMethod' => 'recordingStatusCallbackMethod',
            'RecordingStatusCallbackEvent' => 'recordingStatusCallbackEvent',
        ];
    }
}
