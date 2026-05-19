<?php

declare(strict_types=1);

namespace VoiceML\Model;

final class Application implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $friendlyName,
        public readonly string $apiVersion,
        public readonly string $voiceUrl,
        public readonly bool $voiceCallerIdLookup,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly string $uri,
        public readonly ?string $voiceMethod = null,
        public readonly ?string $voiceFallbackUrl = null,
        public readonly ?string $voiceFallbackMethod = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?string $statusCallbackEvent = null,
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
            friendlyName: (string) ($data['friendly_name'] ?? ''),
            apiVersion: (string) ($data['api_version'] ?? ''),
            voiceUrl: (string) ($data['voice_url'] ?? ''),
            voiceCallerIdLookup: (bool) ($data['voice_caller_id_lookup'] ?? false),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            voiceMethod: isset($data['voice_method']) ? (string) $data['voice_method'] : null,
            voiceFallbackUrl: isset($data['voice_fallback_url']) ? (string) $data['voice_fallback_url'] : null,
            voiceFallbackMethod: isset($data['voice_fallback_method']) ? (string) $data['voice_fallback_method'] : null,
            statusCallback: isset($data['status_callback']) ? (string) $data['status_callback'] : null,
            statusCallbackMethod: isset($data['status_callback_method']) ? (string) $data['status_callback_method'] : null,
            statusCallbackEvent: isset($data['status_callback_event']) ? (string) $data['status_callback_event'] : null,
        );
    }
}
