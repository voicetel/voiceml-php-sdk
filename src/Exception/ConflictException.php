<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 409 — request conflicts with current resource state.
 *
 * Typical case: deleting a queue that still has waiting members.
 */
class ConflictException extends ApiException
{
}
