<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}/Recordings`.
 */
final class StartRecordingRequest extends FormRequest
{
    public function __construct(
        public readonly ?int $recordingMaxDuration = null,
        public readonly ?string $recordingChannels = null,
        public readonly ?bool $playBeep = null,
        public readonly ?string $recordingStatusCallback = null,
        public readonly ?string $recordingStatusCallbackMethod = null,
        public readonly ?string $recordingStatusCallbackEvent = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'RecordingMaxDuration' => 'recordingMaxDuration',
            'RecordingChannels' => 'recordingChannels',
            'PlayBeep' => 'playBeep',
            'RecordingStatusCallback' => 'recordingStatusCallback',
            'RecordingStatusCallbackMethod' => 'recordingStatusCallbackMethod',
            'RecordingStatusCallbackEvent' => 'recordingStatusCallbackEvent',
        ];
    }
}
