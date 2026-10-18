<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A Notification resource (`/Notifications`, `/Calls/{sid}/Notifications`).
 */
final class Notification implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $accountSid,
        public readonly string $callSid,
        public readonly string $errorCode,
        public readonly string $log,
        public readonly string $messageDate,
        public readonly string $moreInfo,
        public readonly string $uri,
        public readonly string $dateCreated,
        public readonly string $dateUpdated,
        public readonly ?string $apiVersion = null,
        public readonly ?string $messageText = null,
        public readonly ?string $requestMethod = null,
        public readonly ?string $requestUrl = null,
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
            callSid: (string) ($data['call_sid'] ?? ''),
            errorCode: (string) ($data['error_code'] ?? ''),
            log: (string) ($data['log'] ?? ''),
            messageDate: (string) ($data['message_date'] ?? ''),
            moreInfo: (string) ($data['more_info'] ?? ''),
            uri: (string) ($data['uri'] ?? ''),
            dateCreated: (string) ($data['date_created'] ?? ''),
            dateUpdated: (string) ($data['date_updated'] ?? ''),
            apiVersion: isset($data['api_version']) ? (string) $data['api_version'] : null,
            messageText: isset($data['message_text']) ? (string) $data['message_text'] : null,
            requestMethod: isset($data['request_method']) ? (string) $data['request_method'] : null,
            requestUrl: isset($data['request_url']) ? (string) $data['request_url'] : null,
        );
    }
}
