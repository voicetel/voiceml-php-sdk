<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 429 — per-account rate limit exceeded. The parsed `Retry-After` delay
 * (seconds) is exposed on {@see ApiException::getRetryAfter()}.
 */
class RateLimitException extends ApiException
{
}
