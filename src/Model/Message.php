<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A Twilio-compatible Message (SMS) resource.
 *
 * Field names mirror the wire shape (snake_case JSON → camelCase PHP via the factory).
 *
 * `numSegments` and `numMedia` are **strings** on the wire — Twilio's documented surface — and
 * are kept as strings here. Don't cast to int unless you're handling the empty/`null` case.
 *
 * `status` is parsed into {@see MessageStatus} when the wire value matches a known case; unknown
 * values land in {@see $statusRaw}. The gateway is fire-and-forget today, so values land at
 * `sent` or `failed`.
 */
final class Message implements Model
{
    /**
     * @param array<string,string>|null $subresourceUris
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $apiVersion,
        public readonly string $to,
        public readonly string $from,
        public readonly string $body,
        public readonly ?MessageStatus $status,
        public readonly string $statusRaw,
        public readonly string $numSegments,
        public readonly string $numMedia,
        public readonly string $direction,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $price = null,
        public readonly ?string $priceUnit = null,
        public readonly ?int $errorCode = null,
        public readonly ?string $errorMessage = null,
        public readonly ?string $messagingServiceSid = null,
        public readonly ?string $dateSent = null,
        public readonly ?array $subresourceUris = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $statusRaw = (string) ($data['status'] ?? '');

        /** @var array<string,string>|null $subresourceUris */
        $subresourceUris = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
            : null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            to: (string) ($data['to'] ?? ''),
            from: (string) ($data['from'] ?? ''),
            body: (string) ($data['body'] ?? ''),
            status: MessageStatus::tryFrom($statusRaw),
            statusRaw: $statusRaw,
            numSegments: (string) ($data['num_segments'] ?? ''),
            numMedia: (string) ($data['num_media'] ?? ''),
            direction: (string) ($data['direction'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            price: array_key_exists('price', $data) && $data['price'] !== null
                ? (string) $data['price']
                : null,
            priceUnit: array_key_exists('price_unit', $data) && $data['price_unit'] !== null
                ? (string) $data['price_unit']
                : null,
            errorCode: array_key_exists('error_code', $data) && $data['error_code'] !== null
                ? (int) $data['error_code']
                : null,
            errorMessage: array_key_exists('error_message', $data) && $data['error_message'] !== null
                ? (string) $data['error_message']
                : null,
            messagingServiceSid: array_key_exists('messaging_service_sid', $data) && $data['messaging_service_sid'] !== null
                ? (string) $data['messaging_service_sid']
                : null,
            dateSent: array_key_exists('date_sent', $data) && $data['date_sent'] !== null
                ? (string) $data['date_sent']
                : null,
            subresourceUris: $subresourceUris,
        );
    }
}
