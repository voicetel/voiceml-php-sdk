<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A Twilio-compatible Call resource.
 *
 * Field names mirror the wire shape (snake_case JSON → camelCase PHP via the factory).
 * Status and direction are parsed into enums when the wire value matches a known case;
 * unknown values land in {@see $statusRaw} / {@see $directionRaw}.
 */
final class Call implements Model
{
    /**
     * @param array<string,string>|null $subresourceUris
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $apiVersion,
        public readonly ?CallStatus $status,
        public readonly string $statusRaw,
        public readonly ?CallDirection $direction,
        public readonly string $directionRaw,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $to = null,
        public readonly ?string $toFormatted = null,
        public readonly ?string $from = null,
        public readonly ?string $fromFormatted = null,
        public readonly ?string $parentCallSid = null,
        public readonly ?string $callerName = null,
        public readonly ?string $forwardedFrom = null,
        public readonly ?string $answeredBy = null,
        public readonly ?string $startTime = null,
        public readonly ?string $endTime = null,
        public readonly ?string $duration = null,
        public readonly ?string $price = null,
        public readonly ?string $priceUnit = null,
        public readonly ?string $phoneNumberSid = null,
        public readonly ?string $annotation = null,
        public readonly ?string $groupSid = null,
        public readonly ?string $queueTime = null,
        public readonly ?string $trunkSid = null,
        public readonly ?array $subresourceUris = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $statusRaw = (string) ($data['status'] ?? '');
        $directionRaw = (string) ($data['direction'] ?? '');

        /** @var array<string,string>|null $subresourceUris */
        $subresourceUris = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
            : null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            status: CallStatus::tryFrom($statusRaw),
            statusRaw: $statusRaw,
            direction: CallDirection::tryFrom($directionRaw),
            directionRaw: $directionRaw,
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            to: isset($data['to']) ? (string) $data['to'] : null,
            toFormatted: isset($data['to_formatted']) ? (string) $data['to_formatted'] : null,
            from: isset($data['from']) ? (string) $data['from'] : null,
            fromFormatted: isset($data['from_formatted']) ? (string) $data['from_formatted'] : null,
            parentCallSid: isset($data['parent_call_sid']) ? (string) $data['parent_call_sid'] : null,
            callerName: isset($data['caller_name']) ? (string) $data['caller_name'] : null,
            forwardedFrom: isset($data['forwarded_from']) ? (string) $data['forwarded_from'] : null,
            answeredBy: isset($data['answered_by']) ? (string) $data['answered_by'] : null,
            startTime: isset($data['start_time']) ? (string) $data['start_time'] : null,
            endTime: isset($data['end_time']) ? (string) $data['end_time'] : null,
            duration: isset($data['duration']) ? (string) $data['duration'] : null,
            price: isset($data['price']) ? (string) $data['price'] : null,
            priceUnit: isset($data['price_unit']) ? (string) $data['price_unit'] : null,
            phoneNumberSid: isset($data['phone_number_sid']) ? (string) $data['phone_number_sid'] : null,
            annotation: isset($data['annotation']) ? (string) $data['annotation'] : null,
            groupSid: isset($data['group_sid']) ? (string) $data['group_sid'] : null,
            queueTime: isset($data['queue_time']) ? (string) $data['queue_time'] : null,
            trunkSid: isset($data['trunk_sid']) ? (string) $data['trunk_sid'] : null,
            subresourceUris: $subresourceUris,
        );
    }
}
