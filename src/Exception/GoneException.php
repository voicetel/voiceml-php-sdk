<?php

declare(strict_types=1);

namespace VoiceML\Exception;

/**
 * HTTP 410 — recording audio is no longer available (no local file, no S3 key).
 */
class GoneException extends ApiException
{
}
