<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * Mixin holding a {@see Transport} reference + helpers for AccountSid pathing.
 */
abstract class Resource
{
    public function __construct(protected readonly Transport $transport)
    {
    }

    /**
     * Build a URL under `/2010-04-01/Accounts/{AccountSid}/…`.
     *
     * Callers pass path segments (e.g. `"Calls"`, sid, `"Recordings"`). Empty segments are
     * skipped; nothing is URL-encoded — callers should pass sids and slugs that don't need
     * escaping (Twilio sids never do).
     */
    protected function path(string ...$parts): string
    {
        $tail = implode('/', array_filter($parts, static fn (string $p): bool => $p !== ''));
        return sprintf('/2010-04-01/Accounts/%s/%s', $this->transport->accountSid(), $tail);
    }
}
