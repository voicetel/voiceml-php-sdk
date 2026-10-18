<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * An account- or recording-scoped Transcription resource
 * (`/Transcriptions`, `/Recordings/{sid}/Transcriptions`).
 *
 * Distinct from the call-scoped live transcription ({@see CallTranscription}).
 */
final class Transcription implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $status,
        public readonly string $recordingSid,
        public readonly string $type,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly string $duration = '',
        public readonly ?string $transcriptionText = null,
        public readonly string $price = '',
        public readonly string $priceUnit = '',
        public readonly string $dateCreated = '',
        public readonly string $dateUpdated = '',
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            recordingSid: (string) ($data['recording_sid'] ?? ''),
            type: (string) ($data['type'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            duration: (string) ($data['duration'] ?? ''),
            transcriptionText: isset($data['transcription_text']) ? (string) $data['transcription_text'] : null,
            price: (string) ($data['price'] ?? ''),
            priceUnit: (string) ($data['price_unit'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
        );
    }
}
