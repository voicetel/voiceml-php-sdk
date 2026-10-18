<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;
use VoiceML\Model\Model;

/**
 * Mixin holding a {@see Transport} reference + path-building helpers.
 *
 * Every caller-supplied path segment is percent-encoded once, here, keeping
 * the RFC 3986 unreserved set (`A-Z a-z 0-9 - . _ ~`): `+`→`%2B`,
 * space→`%20`, `/`→`%2F`, `?`→`%3F`, `#`→`%23`, `%`→`%25`. Resources never
 * encode segments themselves.
 */
abstract class Resource
{
    public function __construct(protected readonly Transport $transport)
    {
    }

    /**
     * Fetch either list-envelope style's next link on this product origin.
     * @return Model|null A new instance of the same list model.
     */
    public function fetchNextPage(Model $page): ?Model
    {
        $uri = property_exists($page, 'nextPageUri') ? $page->nextPageUri : null;
        if (property_exists($page, 'meta') && $page->meta !== null) {
            $uri = $page->meta->nextPageUrl;
        }
        if (!is_string($uri) || $uri === '') {
            return null;
        }
        $raw = $this->transport->requestPageUri($uri);
        if (!is_array($raw)) {
            throw new \VoiceML\Exception\ApiException('could not decode page object', statusCode: 0);
        }
        $decoder = [$page, 'fromArray'];
        if (!is_callable($decoder)) {
            throw new \VoiceML\Exception\ApiException('model has no page decoder', statusCode: 0);
        }

        /** @var callable(array<string,mixed>): Model $decoder */
        return $decoder($raw);
    }

    /**
     * Encode a single path segment per RFC 3986.
     */
    protected static function encodeSegment(string $segment): string
    {
        return rawurlencode($segment);
    }

    /**
     * Build a URL under `/2010-04-01/Accounts/{AccountSid}/…` with the `.json`
     * suffix the account-scoped REST surface requires.
     *
     * Callers pass path segments (e.g. `"Calls"`, sid, `"Recordings"`). Empty
     * segments are skipped; every segment is percent-encoded (see
     * {@see encodeSegment()}). Binary fetches such as `Recordings/{sid}.wav`
     * append their own `.wav` extension via {@see pathRaw()}.
     */
    protected function path(string ...$parts): string
    {
        return $this->pathRaw(...$parts) . '.json';
    }

    /**
     * Build a URL under `/2010-04-01/Accounts/{AccountSid}/…` without the
     * `.json` suffix.
     *
     * Used for endpoints that take a different extension (`.wav` recording
     * audio, `Exports/{Date}`) and binary downloads.
     */
    protected function pathRaw(string ...$parts): string
    {
        return sprintf(
            '/2010-04-01/Accounts/%s/%s',
            self::encodeSegment($this->transport->accountSid()),
            $this->joinParts($parts),
        );
    }

    /**
     * Build a URL under `/v1/…`. The /v1 surface has no `.json` suffix.
     */
    protected function v1Path(string ...$parts): string
    {
        return '/v1/' . $this->joinParts($parts);
    }

    /**
     * Build a URL under `/v2/…`. The /v2 surface has no `.json` suffix.
     */
    protected function v2Path(string ...$parts): string
    {
        return '/v2/' . $this->joinParts($parts);
    }

    /**
     * @param array<int|string, string> $parts
     */
    private function joinParts(array $parts): string
    {
        $encoded = [];
        foreach (array_values($parts) as $part) {
            if ($part !== '') {
                $encoded[] = self::encodeSegment($part);
            }
        }

        return implode('/', $encoded);
    }
}
