<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Messages`. Sent form-encoded.
 *
 * `to` and `body` are required. `from` falls back to the tenant's configured default sender
 * when omitted. `messagingServiceSid` and `statusCallback` are accepted for Twilio compatibility
 * but reserved — outbound SMS is fire-and-forget today.
 */
final class CreateMessageRequest extends FormRequest
{
    public function __construct(
        public readonly string $to,
        public readonly string $body,
        public readonly ?string $from = null,
        public readonly ?string $messagingServiceSid = null,
        public readonly ?string $statusCallback = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'To' => 'to',
            'Body' => 'body',
            'From' => 'from',
            'MessagingServiceSid' => 'messagingServiceSid',
            'StatusCallback' => 'statusCallback',
        ];
    }
}
