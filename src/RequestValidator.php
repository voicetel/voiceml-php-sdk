<?php

declare(strict_types=1);

namespace VoiceML;

/**
 * Validates the `X-Twilio-Signature` header VoiceML puts on every webhook.
 *
 * Algorithm (matches the server's signer):
 *  - Take the full URL exactly as configured, with any `#fragment` stripped.
 *  - For POST requests, append `key + value` for every form parameter, keys
 *    sorted ascending in byte order (repeated keys keep their received value
 *    order). For GET requests, sign the URL alone (pass no params).
 *  - HMAC-SHA1 with the account API key, then Base64 (standard, padded).
 *
 * Like the Twilio-compatible validators, the URL with the default port added
 * or removed (`:443` for https, `:80` for http) validates too, so a proxy
 * that normalises the port doesn't break verification.
 */
final class RequestValidator
{
    public function __construct(
        #[\SensitiveParameter]
        private readonly string $apiKey,
    ) {
    }

    /**
     * Constant-time check of an inbound webhook signature.
     *
     * @param string                $url       Full request URL (fragment stripped if present).
     * @param array<string,string|list<string>> $params  POST form parameters (repeat keys as a list).
     * @param string                $signature `X-Twilio-Signature` header value.
     */
    public function validate(string $url, array $params, string $signature): bool
    {
        $candidates = [self::computeSignature($url, $params, $this->apiKey)];

        $variant = self::toggleDefaultPort($url);
        if ($variant !== null) {
            $candidates[] = self::computeSignature($variant, $params, $this->apiKey);
        }

        foreach ($candidates as $expected) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Compute the signature for a URL + parameter set — the same value the
     * server puts in `X-Twilio-Signature`. Useful for tests and for signing
     * requests you make to your own infrastructure.
     *
     * @param array<string,string|list<string>> $params
     */
    public static function computeSignature(string $url, array $params, #[\SensitiveParameter] string $apiKey): string
    {
        $fragment = strpos($url, '#');
        if ($fragment !== false) {
            $url = substr($url, 0, $fragment);
        }

        $data = $url;
        $sorted = $params;
        ksort($sorted, SORT_STRING);
        foreach ($sorted as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $data .= $key . $item;
                }
            } else {
                $data .= $key . $value;
            }
        }

        return base64_encode(hash_hmac('sha1', $data, $apiKey, true));
    }

    /**
     * Return the URL with the default port toggled (https→`:443` added or
     * removed, http→`:80`), or null when no default port applies.
     */
    private static function toggleDefaultPort(string $url): ?string
    {
        $parts = parse_url($url);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return null;
        }
        $scheme = strtolower($parts['scheme']);
        if ($scheme !== 'https' && $scheme !== 'http') {
            return null;
        }
        $defaultPort = $scheme === 'https' ? 443 : 80;
        $hasPort = isset($parts['port']);
        if ($hasPort && $parts['port'] === $defaultPort) {
            unset($parts['port']);
        } elseif ($hasPort) {
            return null; // non-default port: no normalisation variant
        } else {
            $parts['port'] = $defaultPort;
        }

        return self::buildUrl($parts);
    }

    /**
     * @param array<string,mixed> $parts
     */
    private static function buildUrl(array $parts): string
    {
        $url = strtolower((string) $parts['scheme']) . '://' . $parts['host'];
        if (isset($parts['port'])) {
            $url .= ':' . $parts['port'];
        }
        $url .= $parts['path'] ?? '';
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        return $url;
    }

    /**
     * The API key never appears in var_dump()/print_r() output.
     *
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return ['apiKey' => '***'];
    }
}
