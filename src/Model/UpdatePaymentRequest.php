<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{CallSid}/Payments/{Sid}`. Sent form-encoded.
 *
 * Either advance the session (`capture=...`) or terminate it
 * (`status=complete` or `status=cancel`).
 */
final class UpdatePaymentRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $idempotencyKey = null,
        public readonly ?string $statusCallback = null,
        public readonly ?PaymentCapture $capture = null,
        public readonly ?PaymentSessionStatus $status = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'IdempotencyKey' => 'idempotencyKey',
            'StatusCallback' => 'statusCallback',
            'Capture' => 'capture',
            'Status' => 'status',
        ];
    }
}
