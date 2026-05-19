<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 429 — per-account rate limit exceeded. `Retry-After` header may hint when to retry.
 */
class RateLimitException extends ApiException
{
}
