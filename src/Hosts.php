<?php

declare(strict_types=1);

namespace VoiceML;

/**
 * Per-product host resolution for the VoiceML API.
 *
 * Twilio splits its products across dedicated subdomains (`api.twilio.com`,
 * `conversations.twilio.com`, `messaging.twilio.com`, …). VoiceML mirrors that
 * shape on `voicetel.com`: the Conversations product answers on
 * `conversations.voicetel.com` and the Messaging Service product on
 * `messaging.voicetel.com`, while everything else stays on the default
 * `voiceml.voicetel.com` host. Conversation Service (`IS…`) and Messaging
 * Service (`MG…`) share the identical `/v1/Services` path shape, so the *host*
 * is what disambiguates them on the wire.
 *
 * Given the configured base URL this helper derives the two product hosts by
 * swapping the leftmost `voiceml` label — but only for recognised
 * `*.voicetel.com` hosts. For any other base URL (a self-hosted callBroadcast
 * instance, a test stub, a regional override) the product hosts fall back to
 * the configured host unchanged, so a single-host deployment keeps working. A
 * caller who needs Messaging Service against a custom host points
 * `messagingBaseUrl` (or `conversationsBaseUrl`) at their own subdomain.
 */
final class Hosts
{
    /**
     * Swap the `voiceml` label of a `*.voicetel.com` host for `$product`.
     *
     * Returns `$baseUrl` unchanged when the host is not a
     * `voiceml.*.voicetel.com` style host (e.g. a self-hosted instance), so
     * single-host deployments keep working without special-casing.
     */
    public static function deriveProductHost(string $baseUrl, string $product): string
    {
        $parts = parse_url($baseUrl);
        if ($parts === false || !isset($parts['host'])) {
            return $baseUrl;
        }
        $host = $parts['host'];
        if (!str_ends_with($host, '.voicetel.com')) {
            return $baseUrl;
        }
        $labels = explode('.', $host);
        $idx = array_search('voiceml', $labels, true);
        if ($idx === false) {
            return $baseUrl;
        }
        $labels[$idx] = $product;
        $newHost = implode('.', $labels);

        $scheme = $parts['scheme'] ?? 'https';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '';

        return sprintf('%s://%s%s%s', $scheme, $newHost, $port, $path);
    }

    /**
     * Resolve the `(default, messaging, conversations)` base URLs.
     *
     * Explicit overrides win; otherwise each product host is derived from
     * `$baseUrl`. All three are returned without a trailing slash.
     *
     * @return array{0:string,1:string,2:string}
     */
    public static function resolveProductBaseUrls(
        string $baseUrl,
        ?string $messagingBaseUrl = null,
        ?string $conversationsBaseUrl = null,
    ): array {
        $default = rtrim($baseUrl, '/');
        $messaging = rtrim($messagingBaseUrl ?? self::deriveProductHost($default, 'messaging'), '/');
        $conversations = rtrim(
            $conversationsBaseUrl ?? self::deriveProductHost($default, 'conversations'),
            '/',
        );

        return [$default, $messaging, $conversations];
    }
}
