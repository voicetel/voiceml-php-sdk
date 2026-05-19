<?php

declare(strict_types=1);

namespace VoiceML\Exception;

use Throwable;

/**
 * Raised when the API returns a non-2xx response.
 *
 * Subclasses cover specific status families; catch this class to handle them all.
 * The Twilio-shape error body (`{code, message, more_info, status}`) is parsed into
 * `code` / `message` when present, with the raw payload exposed on `body`.
 */
class ApiException extends VoiceMLException
{
    public readonly int $statusCode;

    /** @var int|string|null */
    public readonly int|string|null $errorCode;

    /** @var mixed */
    public readonly mixed $body;

    public function __construct(
        string $message,
        int $statusCode,
        int|string|null $errorCode = null,
        mixed $body = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->body = $body;
    }
}
