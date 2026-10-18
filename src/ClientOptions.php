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

    /**
     * Base URL for the Messaging Service product (`messaging.voicetel.com`). Derived from
     * {@see $baseUrl} unless explicitly overridden. {@see \VoiceML\Hosts}.
     */
    public readonly string $messagingBaseUrl;

    /**
     * Base URL for the Conversations product (`conversations.voicetel.com`). Derived from
     * {@see $baseUrl} unless explicitly overridden. {@see \VoiceML\Hosts}.
     */
    public readonly string $conversationsBaseUrl;

    public readonly float $timeout;
    public readonly int $maxRetries;
    public readonly string $userAgent;

    public function __construct(
        #[\SensitiveParameter]
        public readonly string $accountSid,
        #[\SensitiveParameter]
        public readonly string $apiKey,
        ?string $baseUrl = null,
        ?float $timeout = null,
        ?int $maxRetries = null,
        ?string $userAgent = null,
        public readonly ?ClientInterface $httpClient = null,
        ?string $messagingBaseUrl = null,
        ?string $conversationsBaseUrl = null,
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
        [$default, $messaging, $conversations] = Hosts::resolveProductBaseUrls(
            $baseUrl ?? self::DEFAULT_BASE_URL,
            $messagingBaseUrl,
            $conversationsBaseUrl,
        );
        $this->baseUrl = $default;
        $this->messagingBaseUrl = $messaging;
        $this->conversationsBaseUrl = $conversations;
        $this->timeout = $timeout ?? self::DEFAULT_TIMEOUT;
        $this->maxRetries = $maxRetries ?? self::DEFAULT_MAX_RETRIES;
        $this->userAgent = $userAgent ?? sprintf('voiceml-php/%s', Version::VERSION);
    }

    /**
     * Redact the API key from var_dump()/print_r() output so a debug statement
     * never leaks the credential into logs.
     *
     * @return array<string,mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'accountSid' => $this->accountSid,
            'apiKey' => '***',
            'baseUrl' => $this->baseUrl,
            'messagingBaseUrl' => $this->messagingBaseUrl,
            'conversationsBaseUrl' => $this->conversationsBaseUrl,
            'timeout' => $this->timeout,
            'maxRetries' => $this->maxRetries,
            'userAgent' => $this->userAgent,
            'httpClient' => $this->httpClient === null ? null : get_class($this->httpClient),
        ];
    }

    /** @return array<string,mixed> */
    public function __serialize(): array
    {
        return $this->__debugInfo();
    }
}
