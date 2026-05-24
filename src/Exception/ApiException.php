<?php

declare(strict_types=1);

namespace VoiceML\Exception;

use Throwable;

/**
 * Raised when the API returns a non-2xx response.
 *
 * Subclasses cover specific status families; catch this class to handle them all.
 * The Twilio-compatible error body (`{code, message, more_info, status}`) is parsed into
 * `code` / `message` / `moreInfo` when present, with the raw payload exposed on `body`.
 *
 * `moreInfo` is the docs URL the server includes for each error code — it points at the
 * canonical explanation of the failure mode (e.g. `https://www.twilio.com/docs/errors/20404`).
 * Matches `TwilioRestException::$moreInfo` / `getMoreInfo()` for drop-in compat with code
 * ported from the Twilio PHP SDK.
 */
class ApiException extends VoiceMLException
{
    public readonly int $statusCode;

    /** @var int|string|null */
    public readonly int|string|null $errorCode;

    /** @var mixed */
    public readonly mixed $body;

    public readonly ?string $moreInfo;

    public function __construct(
        string $message,
        int $statusCode,
        int|string|null $errorCode = null,
        mixed $body = null,
        ?Throwable $previous = null,
        ?string $moreInfo = null,
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->body = $body;
        $this->moreInfo = $moreInfo;
    }

    /**
     * The docs URL the server attached to this error, or null if the body had no
     * `more_info` field. Mirrors `TwilioRestException::getMoreInfo()`.
     */
    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }
}
