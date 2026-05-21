<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class Recording implements Model
{
    /**
     * @param array<string,mixed>|null $encryptionDetails
     * @param array<string,mixed>|null $subresourceUris
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $callSid,
        public readonly string $status,
        public readonly ?string $conferenceSid = null,
        public readonly ?string $source = null,
        public readonly ?int $channels = null,
        public readonly ?string $duration = null,
        public readonly ?string $apiVersion = null,
        public readonly ?string $uri = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $startTime = null,
        public readonly ?string $price = null,
        public readonly ?string $priceUnit = null,
        public readonly ?array $encryptionDetails = null,
        public readonly ?array $subresourceUris = null,
        public readonly ?string $mediaUrl = null,
        public readonly ?int $errorCode = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,mixed>|null $enc */
        $enc = isset($data['encryption_details']) && is_array($data['encryption_details'])
            ? $data['encryption_details']
            : null;
        /** @var array<string,mixed>|null $subs */
        $subs = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? $data['subresource_uris']
            : null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            callSid: (string) ($data['call_sid'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            conferenceSid: isset($data['conference_sid']) ? (string) $data['conference_sid'] : null,
            source: isset($data['source']) ? (string) $data['source'] : null,
            channels: isset($data['channels']) ? (int) $data['channels'] : null,
            duration: isset($data['duration']) ? (string) $data['duration'] : null,
            apiVersion: isset($data['api_version']) ? (string) $data['api_version'] : null,
            uri: isset($data['uri']) ? (string) $data['uri'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            startTime: isset($data['start_time']) ? (string) $data['start_time'] : null,
            price: isset($data['price']) ? (string) $data['price'] : null,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            encryptionDetails: $enc,
            subresourceUris: $subs,
            mediaUrl: isset($data['media_url']) ? (string) $data['media_url'] : null,
            errorCode: array_key_exists('error_code', $data) && $data['error_code'] !== null
                ? (int) $data['error_code']
                : null,
        );
    }
}
