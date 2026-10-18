<?php

declare(strict_types=1);

namespace VoiceML\Model;

use VoiceML\Exception\ValidationException;

/**
 * Body for `POST /Messages`. Sent form-encoded.
 *
 * `to` is required; `from` falls back to the tenant's configured default sender
 * when omitted. `body` and `mediaUrl` are individually optional but at least
 * one must be present (media-only MMS messages are valid). `mediaUrl` is sent
 * as repeated `MediaUrl` keys, one per attachment.
 */
final class CreateMessageRequest extends FormRequest
{
    /**
     * @param list<string>|null $mediaUrl
     */
    public function __construct(
        public readonly string $to,
        public readonly ?string $body = null,
        public readonly ?string $from = null,
        public readonly ?array $mediaUrl = null,
        public readonly ?string $messagingServiceSid = null,
        public readonly ?int $validityPeriod = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
    ) {
        if (($body === null || $body === '') && ($mediaUrl === null || $mediaUrl === [])) {
            throw new ValidationException(
                'CreateMessageRequest: at least one of body or mediaUrl is required'
            );
        }
    }

    protected static function fieldMap(): array
    {
        return [
            'To' => 'to',
            'Body' => 'body',
            'From' => 'from',
            'MediaUrl' => 'mediaUrl',
            'MessagingServiceSid' => 'messagingServiceSid',
            'ValidityPeriod' => 'validityPeriod',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
        ];
    }
}
