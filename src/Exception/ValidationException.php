<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * Raised when a request DTO is constructed with an invalid combination of
 * fields (client-side validation; nothing was sent on the wire).
 */
class ValidationException extends VoiceMLException
{
}
