<?php

declare(strict_types=1);

namespace VoiceML;

use GuzzleHttp\ClientInterface;
use VoiceML\Exception\ConfigurationException;

/**
 * Immutable bag of constructor options for {@see Client}.
 *
 * VoiceML uses HTTP Basic auth: `accountSid` (Twilio-format `AC` + 32 hex) is the username
 * and `apiKey` is the password. Construct one ClientOptions, pass it (or its individual fields)
 * to the {@see Client} constructor, and reuse the client across resource groups.
 */
final class ClientOptions
{
    public const DEFAULT_BASE_URL = 'https://voiceml.voicetel.com';
    public const DEFAULT_TIMEOUT = 30.0;
    public const DEFAULT_MAX_RETRIES = 2;

    public readonly string $baseUrl;
    public readonly float $timeout;
    public readonly int $maxRetries;
    public readonly string $userAgent;

    public function __construct(
        public readonly string $accountSid,
        public readonly string $apiKey,
        ?string $baseUrl = null,
        ?float $timeout = null,
        ?int $maxRetries = null,
        ?string $userAgent = null,
        public readonly ?ClientInterface $httpClient = null,
    ) {
        if ($accountSid === '') {
            throw new ConfigurationException('accountSid is required');
        }
        if ($apiKey === '') {
            throw new ConfigurationException('apiKey is required');
        }
        if (($maxRetries ?? self::DEFAULT_MAX_RETRIES) < 0) {
            throw new ConfigurationException('maxRetries must be >= 0');
        }
        if (($timeout ?? self::DEFAULT_TIMEOUT) < 0) {
            throw new ConfigurationException('timeout must be >= 0');
        }
        $this->baseUrl = rtrim($baseUrl ?? self::DEFAULT_BASE_URL, '/');
        $this->timeout = $timeout ?? self::DEFAULT_TIMEOUT;
        $this->maxRetries = $maxRetries ?? self::DEFAULT_MAX_RETRIES;
        $this->userAgent = $userAgent ?? sprintf('voiceml-php/%s', Version::VERSION);
    }
}
