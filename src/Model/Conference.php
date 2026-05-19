<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class Conference implements Model
{
    /**
     * @param array<string,string>|null $subresourceUris
     */
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $friendlyName,
        public readonly string $status,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly ?string $region = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $reasonConferenceEnded = null,
        public readonly ?string $callSidEndingConference = null,
        public readonly ?array $subresourceUris = null,
        public readonly ?int $memberCount = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,string>|null $subresourceUris */
        $subresourceUris = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
            : null;

        return new self(
            sid: (string) ($data['sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            region: isset($data['region']) ? (string) $data['region'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            reasonConferenceEnded: isset($data['reason_conference_ended']) ? (string) $data['reason_conference_ended'] : null,
            callSidEndingConference: isset($data['call_sid_ending_conference']) ? (string) $data['call_sid_ending_conference'] : null,
            subresourceUris: $subresourceUris,
            memberCount: isset($data['member_count']) ? (int) $data['member_count'] : null,
        );
    }
}
