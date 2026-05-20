<?php

declare(strict_types=1);

namespace VoiceML;

use GuzzleHttp\ClientInterface;
use VoiceML\Exception\ConfigurationException;
use VoiceML\Resource\ApplicationsResource;
use VoiceML\Resource\CallsResource;
use VoiceML\Resource\ConferencesResource;
use VoiceML\Resource\DiagnosticsResource;
use VoiceML\Resource\IncomingPhoneNumbersResource;
use VoiceML\Resource\QueuesResource;
use VoiceML\Resource\RecordingsResource;

/**
 * VoiceML client. Construct once per `{accountSid, apiKey}` pair and reuse.
 *
 * VoiceML uses HTTP Basic auth: the `accountSid` (Twilio-format `AC` + 32 hex) is the username
 * and `apiKey` is the password. Drop-in compatible with the Twilio PHP SDK constructor.
 *
 * ```php
 * use VoiceML\Client;
 * use VoiceML\Model\CreateCallRequest;
 *
 * $client = new Client(accountSid: 'AC…', apiKey: '…');
 * $call = $client->calls->create(new CreateCallRequest(
 *     to: '+18005551234',
 *     from: '+18005550000',
 *     url: 'https://example.com/twiml',
 * ));
 * ```
 *
 * For drop-in compatibility with the Twilio PHP SDK's `$authToken` constructor argument,
 * the credential can also be passed as `authToken:` — it is treated as an alias for
 * `apiKey:`. Passing both raises {@see \VoiceML\Exception\ConfigurationException}.
 */
final class Client
{
    public readonly CallsResource $calls;
    public readonly ConferencesResource $conferences;
    public readonly QueuesResource $queues;
    public readonly ApplicationsResource $applications;
    public readonly RecordingsResource $recordings;
    public readonly IncomingPhoneNumbersResource $incomingPhoneNumbers;
    public readonly DiagnosticsResource $diagnostics;

    private readonly Transport $transport;
    private readonly ClientOptions $options;

    public function __construct(
        string $accountSid,
        ?string $apiKey = null,
        ?string $baseUrl = null,
        ?float $timeout = null,
        ?int $maxRetries = null,
        ?string $userAgent = null,
        ?ClientInterface $httpClient = null,
        ?string $authToken = null,
    ) {
        if ($apiKey !== null && $authToken !== null) {
            throw new ConfigurationException(
                'pass only one of apiKey or authToken; authToken is an alias for apiKey'
            );
        }
        $resolvedKey = $apiKey ?? $authToken;
        if ($resolvedKey === null) {
            throw new ConfigurationException('apiKey (or authToken) is required');
        }

        $this->options = new ClientOptions(
            accountSid: $accountSid,
            apiKey: $resolvedKey,
            baseUrl: $baseUrl,
            timeout: $timeout,
            maxRetries: $maxRetries,
            userAgent: $userAgent,
            httpClient: $httpClient,
        );
        $this->transport = new Transport($this->options);

        $this->calls = new CallsResource($this->transport);
        $this->conferences = new ConferencesResource($this->transport);
        $this->queues = new QueuesResource($this->transport);
        $this->applications = new ApplicationsResource($this->transport);
        $this->recordings = new RecordingsResource($this->transport);
        $this->incomingPhoneNumbers = new IncomingPhoneNumbersResource($this->transport);
        $this->diagnostics = new DiagnosticsResource($this->transport);
    }

    /**
     * Construct from a {@see ClientOptions} value directly. Useful when the options object
     * is shared between multiple subsystems.
     */
    public static function fromOptions(ClientOptions $options): self
    {
        return new self(
            accountSid: $options->accountSid,
            apiKey: $options->apiKey,
            baseUrl: $options->baseUrl,
            timeout: $options->timeout,
            maxRetries: $options->maxRetries,
            userAgent: $options->userAgent,
            httpClient: $options->httpClient,
        );
    }

    public function accountSid(): string
    {
        return $this->options->accountSid;
    }

    public function baseUrl(): string
    {
        return $this->options->baseUrl;
    }

    public function transport(): Transport
    {
        return $this->transport;
    }
}
