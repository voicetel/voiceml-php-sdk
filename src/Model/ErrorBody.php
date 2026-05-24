<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Twilio-compatible error body — what the server returns for non-2xx responses.
 *
 * The transport wraps this into a {@see \VoiceML\Exception\ApiException} (or a subclass) with
 * the parsed payload exposed on `body`. Code is the numeric Twilio code (e.g. 21211).
 */
final class ErrorBody implements Model
{
    public function __construct(
        public readonly ?int $code = null,
        public readonly ?string $message = null,
        public readonly ?string $moreInfo = null,
        public readonly ?int $status = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: isset($data['code']) ? (int) $data['code'] : null,
            message: isset($data['message']) ? (string) $data['message'] : null,
            moreInfo: isset($data['more_info']) ? (string) $data['more_info'] : null,
            status: isset($data['status']) ? (int) $data['status'] : null,
        );
    }
}
