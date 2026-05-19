<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls`. Sent form-encoded.
 *
 * Set at most one of `url` / `twiml` / `applicationSid` (Twiml wins if multiple are set —
 * Twilio's documented precedence).
 */
final class CreateCallRequest extends FormRequest
{
    /**
     * @param list<string>|null $statusCallbackEvent
     */
    public function __construct(
        public readonly string $to,
        public readonly string $from,
        public readonly ?string $url = null,
        public readonly ?string $method = null,
        public readonly ?string $twiml = null,
        public readonly ?string $applicationSid = null,
        public readonly ?string $fallbackUrl = null,
        public readonly ?string $fallbackMethod = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?array $statusCallbackEvent = null,
        public readonly ?string $machineDetection = null,
        public readonly ?int $machineDetectionTimeout = null,
        public readonly ?int $machineDetectionSpeechThreshold = null,
        public readonly ?int $machineDetectionSpeechEndThreshold = null,
        public readonly ?int $machineDetectionSilenceTimeout = null,
        public readonly ?string $asyncAmdStatusCallback = null,
        public readonly ?string $asyncAmdStatusCallbackMethod = null,
        public readonly ?bool $record = null,
        public readonly ?string $recordingStatusCallback = null,
        public readonly ?string $recordingStatusCallbackMethod = null,
        public readonly ?string $recordingStatusCallbackEvent = null,
        public readonly ?string $recordingChannels = null,
        public readonly ?string $recordingTrack = null,
        public readonly ?string $trim = null,
        public readonly ?int $timeout = null,
        public readonly ?string $sendDigits = null,
        public readonly ?string $callerId = null,
        public readonly ?string $callReason = null,
        public readonly ?string $sipAuthUsername = null,
        public readonly ?string $sipAuthPassword = null,
        public readonly ?string $byoc = null,
        public readonly ?bool $asyncAmd = null,
        public readonly ?string $callToken = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'To' => 'to',
            'From' => 'from',
            'Url' => 'url',
            'Method' => 'method',
            'Twiml' => 'twiml',
            'ApplicationSid' => 'applicationSid',
            'FallbackUrl' => 'fallbackUrl',
            'FallbackMethod' => 'fallbackMethod',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
            'StatusCallbackEvent' => 'statusCallbackEvent',
            'MachineDetection' => 'machineDetection',
            'MachineDetectionTimeout' => 'machineDetectionTimeout',
            'MachineDetectionSpeechThreshold' => 'machineDetectionSpeechThreshold',
            'MachineDetectionSpeechEndThreshold' => 'machineDetectionSpeechEndThreshold',
            'MachineDetectionSilenceTimeout' => 'machineDetectionSilenceTimeout',
            'AsyncAmdStatusCallback' => 'asyncAmdStatusCallback',
            'AsyncAmdStatusCallbackMethod' => 'asyncAmdStatusCallbackMethod',
            'Record' => 'record',
            'RecordingStatusCallback' => 'recordingStatusCallback',
            'RecordingStatusCallbackMethod' => 'recordingStatusCallbackMethod',
            'RecordingStatusCallbackEvent' => 'recordingStatusCallbackEvent',
            'RecordingChannels' => 'recordingChannels',
            'RecordingTrack' => 'recordingTrack',
            'Trim' => 'trim',
            'Timeout' => 'timeout',
            'SendDigits' => 'sendDigits',
            'CallerId' => 'callerId',
            'CallReason' => 'callReason',
            'SipAuthUsername' => 'sipAuthUsername',
            'SipAuthPassword' => 'sipAuthPassword',
            'Byoc' => 'byoc',
            'AsyncAmd' => 'asyncAmd',
            'CallToken' => 'callToken',
        ];
    }
}
