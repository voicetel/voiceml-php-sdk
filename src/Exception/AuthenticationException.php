<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 401 — Basic auth missing, account unknown, key wrong, or source IP not allowed.
 *
 * The server intentionally returns an identical 401 for all four failure modes — see the
 * Twilio-compat spec's `Unauthorized` response description.
 */
class AuthenticationException extends ApiException
{
}
