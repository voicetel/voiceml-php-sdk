<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 403 — authenticated, but not allowed to perform this action.
 */
class PermissionDeniedException extends ApiException
{
}
