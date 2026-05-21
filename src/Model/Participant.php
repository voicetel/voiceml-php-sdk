<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class Participant implements Model
{
    public function __construct(
        public readonly string $callSid,
        public readonly string $conferenceSid,
        public readonly string $accountSid,
        public readonly bool $muted,
        public readonly bool $hold,
        public readonly bool $coaching,
        public readonly ?string $callSidToCoach = null,
        public readonly string $queueTime = '0',
        public readonly bool $startConferenceOnEnter,
        public readonly bool $endConferenceOnExit,
        public readonly string $status,
        public readonly string $apiVersion,
        public readonly string $uri,
        public readonly ?string $label = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            callSid: (string) ($data['call_sid'] ?? ''),
            conferenceSid: (string) ($data['conference_sid'] ?? ''),
            accountSid: (string) ($data['account_sid'] ?? ''),
            muted: (bool) ($data['muted'] ?? false),
            hold: (bool) ($data['hold'] ?? false),
            coaching: (bool) ($data['coaching'] ?? false),
            callSidToCoach: isset($data['call_sid_to_coach']) && $data['call_sid_to_coach'] !== ''
                ? (string) $data['call_sid_to_coach']
                : null,
            queueTime: (string) ($data['queue_time'] ?? '0'),
            startConferenceOnEnter: (bool) ($data['start_conference_on_enter'] ?? false),
            endConferenceOnExit: (bool) ($data['end_conference_on_exit'] ?? false),
            status: (string) ($data['status'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            label: isset($data['label']) ? (string) $data['label'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
