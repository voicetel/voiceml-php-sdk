<?php

declare(strict_types=1);

namespace VoiceML\Exception;

use RuntimeException;

/**
 * Base class for every error raised by this SDK. Catch this to handle any SDK error;
 * catch a specific subclass (AuthenticationException, NotFoundException, ...) to branch
 * on HTTP status family.
 */
class VoiceMLException extends RuntimeException
{
}
