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
     *
     * The final segment is suffixed with `.json` to match Twilio's wire convention (and the
     * v0.5.x server spec, which requires it). Binary fetches such as `Recordings/{sid}.wav`
     * append their own `.wav` extension via {@see pathRaw()} and bypass the `.json` suffix.
     */
    protected function path(string ...$parts): string
    {
        return $this->pathRaw(...$parts) . '.json';
    }

    /**
     * Build a URL under `/2010-04-01/Accounts/{AccountSid}/…` without the `.json` suffix.
     *
     * Use this for endpoints that take a different extension (`.wav` for recording audio) or
     * for the small set of unsuffixed compatibility paths. Account-scoped REST endpoints
     * should use {@see path()} instead.
     */
    protected function pathRaw(string ...$parts): string
    {
        $tail = implode('/', array_filter($parts, static fn (string $p): bool => $p !== ''));
        return sprintf('/2010-04-01/Accounts/%s/%s', $this->transport->accountSid(), $tail);
    }
}
