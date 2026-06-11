<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Messages/{Sid}`. Sent form-encoded.
 *
 * Two operations on the same endpoint, both Twilio-documented:
 *   * `body=""` — redact the message text (non-empty `Body` is ignored).
 *   * `status="canceled"` — returns 21610 today because the VoiceTel SDK 2.2
 *     gateway is fire-and-forget; included for compatibility.
 */
final class UpdateMessageRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $body = null,
        public readonly ?string $status = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Body' => 'body',
            'Status' => 'status',
        ];
    }
}
