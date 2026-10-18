<?php

declare(strict_types=1);

namespace VoiceML\Exception;

use Throwable;

/**
 * Raised when the API returns a non-2xx response.
 *
 * Subclasses cover specific status families; catch this class to handle them all.
 * The VoiceML-compatible error body (`{code, message, more_info, status}`) is parsed into
 * `code` / `message` / `moreInfo` when present, with the raw payload exposed on `body`.
 *
 * When the server sent a parseable `Retry-After` header (delta-seconds or HTTP-date),
 * the delay in seconds is exposed on `retryAfter` / `getRetryAfter()` — most useful on
 * {@see RateLimitException}.
 */
class ApiException extends VoiceMLException
{
    public readonly int $statusCode;

    /** @var int|string|null */
    public readonly int|string|null $errorCode;

    /** @var mixed */
    public readonly mixed $body;

    public readonly ?string $moreInfo;

    public readonly ?float $retryAfter;

    public function __construct(
        string $message,
        int $statusCode,
        int|string|null $errorCode = null,
        mixed $body = null,
        ?Throwable $previous = null,
        ?string $moreInfo = null,
        ?float $retryAfter = null,
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->body = $body;
        $this->moreInfo = $moreInfo;
        $this->retryAfter = $retryAfter;
    }

    /**
     * The docs URL the server attached to this error, or null if the body had no
     * `more_info` field.
     */
    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }

    /**
     * The parsed `Retry-After` delay in seconds (delta-seconds or HTTP-date),
     * or null when the header was absent or unparseable.
     */
    public function getRetryAfter(): ?float
    {
        return $this->retryAfter;
    }
}
