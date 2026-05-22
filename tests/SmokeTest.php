<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use VoiceML\Client;
use VoiceML\ClientOptions;
use VoiceML\Exception\AuthenticationException;
use VoiceML\Exception\ConfigurationException;
use VoiceML\Exception\ConflictException;
use VoiceML\Exception\NotFoundException;
use VoiceML\Exception\NotImplementedApiException;
use VoiceML\Exception\RateLimitException;
use RuntimeException;
use VoiceML\Model\CreateCallRequest;
use VoiceML\Model\CreateIncomingPhoneNumberRequest;
use VoiceML\Model\IncomingPhoneNumber;
use VoiceML\Model\ListCallsParams;
use VoiceML\Model\Participant;
use VoiceML\Model\Recording;
use VoiceML\Model\UpdateIncomingPhoneNumberRequest;
use VoiceML\Model\UpdateParticipantRequest;
use VoiceML\Resource\CallsResource;
use VoiceML\Resource\IncomingPhoneNumbersResource;
use VoiceML\Version;

final class SmokeTest extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const CALL_SID = 'CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    private const PHONE_NUMBER_SID = 'PN0123456789abcdef0123456789abcdef';

    /**
     * @return array{client: Client, mock: MockHandler, history: array<int,array<string,mixed>>}
     */
    private function makeClient(array $responses, ?float $timeout = null, ?int $maxRetries = null): array
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        /** @var array<int,array<string,mixed>> $history */
        $history = [];
        $stack->push(Middleware::history($history));

        $guzzle = new GuzzleClient([
            'handler' => $stack,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);

        $client = new Client(
            accountSid: self::ACCOUNT_SID,
            apiKey: self::API_KEY,
            timeout: $timeout,
            maxRetries: $maxRetries,
            httpClient: $guzzle,
        );

        return ['client' => $client, 'mock' => $mock, 'history' => &$history];
    }

    public function testClientConstructionRequiresAccountSid(): void
    {
        $this->expectException(ConfigurationException::class);
        new Client(accountSid: '', apiKey: 'k');
    }

    public function testClientConstructionRequiresApiKey(): void
    {
        $this->expectException(ConfigurationException::class);
        new Client(accountSid: 'AC123', apiKey: '');
    }

    public function testDefaultBaseUrl(): void
    {
        $client = new Client(accountSid: self::ACCOUNT_SID, apiKey: self::API_KEY);
        self::assertSame(ClientOptions::DEFAULT_BASE_URL, $client->baseUrl());
        self::assertSame(self::ACCOUNT_SID, $client->accountSid());
        self::assertInstanceOf(CallsResource::class, $client->calls);
    }

    public function testCallsCreateSendsFormBodyWithBasicAuth(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'sid' => self::CALL_SID,
                'account_sid' => self::ACCOUNT_SID,
                'api_version' => '2010-04-01',
                'to' => '+18005551234',
                'from' => '+18005550000',
                'status' => 'queued',
                'direction' => 'outbound-api',
                'date_created' => 'now',
                'date_updated' => 'now',
                'uri' => '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/Calls/' . self::CALL_SID,
            ])),
        ]);

        $call = $bag['client']->calls->create(new CreateCallRequest(
            to: '+18005551234',
            from: '+18005550000',
            url: 'https://example.com/twiml',
        ));

        self::assertSame(self::CALL_SID, $call->sid);
        self::assertSame('queued', $call->statusRaw);

        self::assertCount(1, $bag['history']);
        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        self::assertSame('POST', $request->getMethod());
        // v0.5.x: paths carry the `.json` Twilio-wire suffix.
        self::assertStringContainsString(
            '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/Calls.json',
            (string) $request->getUri(),
        );
        self::assertSame(
            'application/x-www-form-urlencoded',
            $request->getHeaderLine('Content-Type'),
        );
        // Basic auth = base64("AccountSid:ApiKey")
        $expectedAuth = 'Basic ' . base64_encode(self::ACCOUNT_SID . ':' . self::API_KEY);
        self::assertSame($expectedAuth, $request->getHeaderLine('Authorization'));

        $body = (string) $request->getBody();
        parse_str($body, $parsed);
        self::assertSame('+18005551234', $parsed['To']);
        self::assertSame('+18005550000', $parsed['From']);
        self::assertSame('https://example.com/twiml', $parsed['Url']);
    }

    public function testCallsListAddsStartTimeFilterQueryNames(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'calls' => [],
                'page' => 0,
                'page_size' => 50,
            ])),
        ]);

        $bag['client']->calls->list(
            status: 'in-progress',
            startTimeGte: '2026-05-01',
            startTimeLte: '2026-05-19',
            pageSize: 25,
        );

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        $uri = (string) $request->getUri();
        self::assertStringContainsString(
            '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/Calls.json',
            $uri,
        );
        $query = $request->getUri()->getQuery();
        // Literal Twilio wire query names.
        self::assertStringContainsString('StartTime%3E%3D=2026-05-01', $query);
        self::assertStringContainsString('StartTime%3C%3D=2026-05-19', $query);
        self::assertStringContainsString('Status=in-progress', $query);
        self::assertStringContainsString('PageSize=25', $query);
    }

    public function testBooleanFormFieldsEncodeAsTrueAndFalseStrings(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'call_sid' => self::CALL_SID,
                'conference_sid' => 'CF00000000000000000000000000000001',
                'account_sid' => self::ACCOUNT_SID,
                'muted' => true,
                'hold' => false,
                'start_conference_on_enter' => true,
                'end_conference_on_exit' => false,
                'status' => 'connected',
                'api_version' => '2010-04-01',
                'uri' => '/uri',
            ])),
        ]);

        $bag['client']->conferences->updateParticipant(
            conferenceSid: 'CF00000000000000000000000000000001',
            callSid: self::CALL_SID,
            body: new UpdateParticipantRequest(muted: true, hold: false),
        );

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        parse_str((string) $request->getBody(), $parsed);
        self::assertSame('true', $parsed['Muted']);
        self::assertSame('false', $parsed['Hold']);
    }

    public function testErrorMapping401(): void
    {
        $bag = $this->makeClient([
            new Response(401, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20003,
                'message' => 'Authentication Error',
                'status' => 401,
            ])),
        ]);

        $this->expectException(AuthenticationException::class);
        $bag['client']->calls->get(self::CALL_SID);
    }

    public function testErrorMapping404(): void
    {
        $bag = $this->makeClient([
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20404,
                'message' => 'Not Found',
                'status' => 404,
            ])),
        ]);

        try {
            $bag['client']->calls->get(self::CALL_SID);
            self::fail('expected NotFoundException');
        } catch (NotFoundException $e) {
            self::assertSame(404, $e->statusCode);
            self::assertSame(20404, $e->errorCode);
            self::assertSame('Not Found', $e->getMessage());
        }
    }

    public function testErrorMapping429(): void
    {
        // No retry: pass maxRetries=0.
        $bag = $this->makeClient([
            new Response(429, ['Retry-After' => '1', 'Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20429,
                'message' => 'Too Many Requests',
                'status' => 429,
            ])),
        ], maxRetries: 0);

        $this->expectException(RateLimitException::class);
        $bag['client']->calls->get(self::CALL_SID);
    }

    public function testErrorMapping501UserDefinedMessages(): void
    {
        $bag = $this->makeClient([
            new Response(501, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20501,
                'message' => 'Not Implemented',
                'status' => 501,
            ])),
        ]);

        $this->expectException(NotImplementedApiException::class);
        $bag['client']->calls->sendUserDefinedMessage(self::CALL_SID, ['foo' => 'bar']);
    }

    public function testErrorMapping409(): void
    {
        $bag = $this->makeClient([
            new Response(409, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20409,
                'message' => 'Queue not empty',
                'status' => 409,
            ])),
        ]);

        $this->expectException(ConflictException::class);
        $bag['client']->queues->delete('QU00000000000000000000000000000001');
    }

    public function testRetryOn503ThenSuccess(): void
    {
        $bag = $this->makeClient([
            new Response(503, [], '{"code":20500,"message":"Service Unavailable","status":503}'),
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'sid' => self::CALL_SID,
                'account_sid' => self::ACCOUNT_SID,
                'api_version' => '2010-04-01',
                'status' => 'in-progress',
                'direction' => 'inbound',
                'date_created' => 'now',
                'date_updated' => 'now',
                'uri' => '/uri',
            ])),
        ], maxRetries: 1);

        $call = $bag['client']->calls->get(self::CALL_SID);
        self::assertSame(self::CALL_SID, $call->sid);
        self::assertCount(2, $bag['history']);
    }

    // ---------------------------------------------------------------------
    // v0.5.0 additions
    // ---------------------------------------------------------------------

    public function testAuthTokenAliasResolvesAsApiKey(): void
    {
        // Passing authToken: instead of apiKey: must produce identical Basic-auth header.
        $bag = $this->makeClientWithCreds(
            responses: [
                new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                    'sid' => self::CALL_SID,
                    'account_sid' => self::ACCOUNT_SID,
                    'api_version' => '2010-04-01',
                    'status' => 'in-progress',
                    'direction' => 'inbound',
                    'date_created' => 'now',
                    'date_updated' => 'now',
                    'uri' => '/uri',
                ])),
            ],
            apiKey: null,
            authToken: self::API_KEY,
        );

        $bag['client']->calls->get(self::CALL_SID);

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        $expectedAuth = 'Basic ' . base64_encode(self::ACCOUNT_SID . ':' . self::API_KEY);
        self::assertSame($expectedAuth, $request->getHeaderLine('Authorization'));
    }

    public function testAuthTokenAndApiKeyTogetherThrows(): void
    {
        $this->expectException(ConfigurationException::class);
        new Client(
            accountSid: self::ACCOUNT_SID,
            apiKey: 'k1',
            authToken: 'k2',
        );
    }

    public function testMissingApiKeyAndAuthTokenThrows(): void
    {
        $this->expectException(ConfigurationException::class);
        new Client(accountSid: self::ACCOUNT_SID);
    }

    public function testApiExceptionExposesMoreInfo(): void
    {
        $bag = $this->makeClient([
            new Response(404, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20404,
                'message' => 'Not Found',
                'more_info' => 'https://www.twilio.com/docs/errors/20404',
                'status' => 404,
            ])),
        ]);

        try {
            $bag['client']->calls->get(self::CALL_SID);
            self::fail('expected NotFoundException');
        } catch (NotFoundException $e) {
            self::assertSame('https://www.twilio.com/docs/errors/20404', $e->moreInfo);
            self::assertSame('https://www.twilio.com/docs/errors/20404', $e->getMoreInfo());
        }
    }

    public function testApiExceptionMoreInfoNullWhenAbsent(): void
    {
        $bag = $this->makeClient([
            new Response(409, ['Content-Type' => 'application/json'], (string) json_encode([
                'code' => 20409,
                'message' => 'Conflict',
                'status' => 409,
            ])),
        ]);

        try {
            $bag['client']->calls->get(self::CALL_SID);
            self::fail('expected ConflictException');
        } catch (ConflictException $e) {
            self::assertNull($e->moreInfo);
            self::assertNull($e->getMoreInfo());
        }
    }

    public function testIncomingPhoneNumbersResourceIsWired(): void
    {
        $client = new Client(accountSid: self::ACCOUNT_SID, apiKey: self::API_KEY);
        self::assertInstanceOf(IncomingPhoneNumbersResource::class, $client->incomingPhoneNumbers);
    }

    public function testIncomingPhoneNumbersListHitsDotJsonPathAndFilter(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'incoming_phone_numbers' => [
                    [
                        'sid' => self::PHONE_NUMBER_SID,
                        'account_sid' => self::ACCOUNT_SID,
                        'phone_number' => '+18005551234',
                        'api_version' => '2010-04-01',
                        'uri' => '/2010-04-01/Accounts/' . self::ACCOUNT_SID
                            . '/IncomingPhoneNumbers/' . self::PHONE_NUMBER_SID . '.json',
                        'capabilities' => ['voice' => true, 'sms' => false, 'mms' => false, 'fax' => false],
                        'voice_url' => 'https://example.com/voice',
                        'voice_method' => 'POST',
                        'date_created' => 'now',
                        'date_updated' => 'now',
                    ],
                ],
                'page' => 0,
                'page_size' => 50,
                'total' => 1,
            ])),
        ]);

        $list = $bag['client']->incomingPhoneNumbers->list(phoneNumber: '+18005551234');

        self::assertCount(1, $list->incomingPhoneNumbers);
        self::assertSame(self::PHONE_NUMBER_SID, $list->incomingPhoneNumbers[0]->sid);
        self::assertSame('+18005551234', $list->incomingPhoneNumbers[0]->phoneNumber);

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        $uri = (string) $request->getUri();
        self::assertStringContainsString(
            '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/IncomingPhoneNumbers.json',
            $uri,
        );
        // E.164 leading `+` round-trips through Guzzle as `%2B`.
        $query = $request->getUri()->getQuery();
        self::assertStringContainsString('PhoneNumber=%2B18005551234', $query);
    }

    public function testIncomingPhoneNumbersCreatePostsFormBody(): void
    {
        $bag = $this->makeClient([
            new Response(201, ['Content-Type' => 'application/json'], (string) json_encode([
                'sid' => self::PHONE_NUMBER_SID,
                'account_sid' => self::ACCOUNT_SID,
                'phone_number' => '+18005551234',
                'api_version' => '2010-04-01',
                'uri' => '/uri',
                'capabilities' => ['voice' => true, 'sms' => false, 'mms' => false, 'fax' => false],
                'voice_url' => 'https://example.com/voice',
                'voice_method' => 'POST',
            ])),
        ]);

        $ipn = $bag['client']->incomingPhoneNumbers->create(new CreateIncomingPhoneNumberRequest(
            phoneNumber: '+18005551234',
            voiceUrl: 'https://example.com/voice',
            voiceMethod: 'POST',
        ));

        self::assertSame(self::PHONE_NUMBER_SID, $ipn->sid);

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertStringContainsString(
            '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/IncomingPhoneNumbers.json',
            (string) $request->getUri(),
        );
        parse_str((string) $request->getBody(), $parsed);
        self::assertSame('+18005551234', $parsed['PhoneNumber']);
        self::assertSame('https://example.com/voice', $parsed['VoiceUrl']);
        self::assertSame('POST', $parsed['VoiceMethod']);
    }

    public function testIncomingPhoneNumbersGetUsesSidInPath(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'sid' => self::PHONE_NUMBER_SID,
                'account_sid' => self::ACCOUNT_SID,
                'phone_number' => '+18005551234',
                'api_version' => '2010-04-01',
                'uri' => '/uri',
                'capabilities' => ['voice' => true, 'sms' => false, 'mms' => false, 'fax' => false],
            ])),
        ]);

        $ipn = $bag['client']->incomingPhoneNumbers->get(self::PHONE_NUMBER_SID);
        self::assertSame(self::PHONE_NUMBER_SID, $ipn->sid);

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        self::assertStringContainsString(
            '/IncomingPhoneNumbers/' . self::PHONE_NUMBER_SID . '.json',
            (string) $request->getUri(),
        );
    }

    public function testIncomingPhoneNumbersUpdatePostsPartialBody(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'sid' => self::PHONE_NUMBER_SID,
                'account_sid' => self::ACCOUNT_SID,
                'phone_number' => '+18005551234',
                'api_version' => '2010-04-01',
                'uri' => '/uri',
                'capabilities' => ['voice' => true, 'sms' => false, 'mms' => false, 'fax' => false],
                'voice_url' => 'https://example.com/v2',
            ])),
        ]);

        $bag['client']->incomingPhoneNumbers->update(
            self::PHONE_NUMBER_SID,
            new UpdateIncomingPhoneNumberRequest(voiceUrl: 'https://example.com/v2'),
        );

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        self::assertSame('POST', $request->getMethod());
        self::assertStringContainsString(
            '/IncomingPhoneNumbers/' . self::PHONE_NUMBER_SID . '.json',
            (string) $request->getUri(),
        );
        parse_str((string) $request->getBody(), $parsed);
        self::assertSame('https://example.com/v2', $parsed['VoiceUrl']);
        self::assertArrayNotHasKey('VoiceMethod', $parsed);
    }

    public function testIncomingPhoneNumbersDeleteIssuesDeleteWithDotJson(): void
    {
        $bag = $this->makeClient([
            new Response(204, [], ''),
        ]);

        $bag['client']->incomingPhoneNumbers->delete(self::PHONE_NUMBER_SID);

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        self::assertSame('DELETE', $request->getMethod());
        self::assertStringContainsString(
            '/IncomingPhoneNumbers/' . self::PHONE_NUMBER_SID . '.json',
            (string) $request->getUri(),
        );
    }

    public function testRecordingsAudioPathKeepsDotWavAndOmitsDotJson(): void
    {
        // Body is just any bytes — focus is on the URL shape.
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'audio/wav'], 'RIFFsentinel'),
        ]);

        $bag['client']->recordings->getAudio('RE00000000000000000000000000000001');

        /** @var Request $request */
        $request = $bag['history'][0]['request'];
        $uri = (string) $request->getUri();
        self::assertStringContainsString('/Recordings/RE00000000000000000000000000000001.wav', $uri);
        self::assertStringNotContainsString('.json', $uri);
    }

    // ---------------------------------------------------------------------
    // v0.6.0 additions — Twilio-compat IncomingPhoneNumber field set
    // ---------------------------------------------------------------------

    public function testIncomingPhoneNumberDeserializesFullTwilioShape(): void
    {
        $payload = [
            'sid' => self::PHONE_NUMBER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'phone_number' => '+18005551234',
            'friendly_name' => 'Main Line',
            'api_version' => '2010-04-01',
            'uri' => '/2010-04-01/Accounts/' . self::ACCOUNT_SID
                . '/IncomingPhoneNumbers/' . self::PHONE_NUMBER_SID . '.json',
            'origin' => '',
            'beta' => false,
            'type' => 'local',
            'capabilities' => [
                'voice' => true,
                'sms' => false,
                'mms' => false,
                'fax' => false,
            ],
            'voice_url' => 'https://example.com/voice',
            'voice_method' => 'POST',
            'voice_fallback_url' => 'https://example.com/voice-fallback',
            'voice_fallback_method' => 'POST',
            'voice_application_sid' => '',
            'voice_caller_id_lookup' => false,
            'voice_receive_mode' => 'voice',
            'sms_url' => '',
            'sms_method' => '',
            'sms_fallback_url' => '',
            'sms_fallback_method' => '',
            'sms_application_sid' => '',
            'status_callback' => '',
            'status_callback_method' => '',
            'trunk_sid' => '',
            'address_sid' => '',
            'address_requirements' => 'none',
            'identity_sid' => '',
            'bundle_sid' => '',
            'emergency_status' => '',
            'emergency_address_sid' => '',
            'emergency_address_status' => '',
            'status' => '',
            'date_created' => 'now',
            'date_updated' => 'now',
        ];

        $ipn = IncomingPhoneNumber::fromArray($payload);

        // Capabilities sub-object is wired up and required fields land verbatim.
        self::assertTrue($ipn->capabilities->voice);
        self::assertFalse($ipn->capabilities->sms);
        self::assertFalse($ipn->capabilities->mms);
        self::assertFalse($ipn->capabilities->fax);

        // Twilio-compat scalars round-trip — including empty-string enum defaults.
        self::assertSame('', $ipn->origin);
        self::assertFalse($ipn->beta);
        self::assertSame('local', $ipn->type);
        self::assertFalse($ipn->voiceCallerIdLookup);
        self::assertSame('voice', $ipn->voiceReceiveMode);
        self::assertSame('none', $ipn->addressRequirements);
        self::assertSame('', $ipn->emergencyStatus);
        self::assertSame('', $ipn->status);
    }

    public function testIncomingPhoneNumberMissingCapabilitiesThrows(): void
    {
        $this->expectException(RuntimeException::class);
        IncomingPhoneNumber::fromArray([
            'sid' => self::PHONE_NUMBER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'phone_number' => '+18005551234',
            'api_version' => '2010-04-01',
            'uri' => '/uri',
            // capabilities deliberately omitted
        ]);
    }

    public function testIncomingPhoneNumberCapabilitiesMissingFlagThrows(): void
    {
        $this->expectException(RuntimeException::class);
        IncomingPhoneNumber::fromArray([
            'sid' => self::PHONE_NUMBER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'phone_number' => '+18005551234',
            'api_version' => '2010-04-01',
            'uri' => '/uri',
            'capabilities' => ['voice' => true, 'sms' => false, 'mms' => false],
            // fax flag deliberately omitted
        ]);
    }

    // ---------------------------------------------------------------------
    // v0.6.2 additions — Recording.media_url (D5) + IPN.type (D6) round-trip
    // ---------------------------------------------------------------------

    public function testRecordingFromArrayPopulatesMediaUrlWhenPresent(): void
    {
        $payload = [
            'sid' => 'RE00000000000000000000000000000001',
            'account_sid' => self::ACCOUNT_SID,
            'call_sid' => self::CALL_SID,
            'status' => 'completed',
            'media_url' => 'https://api.voiceml.example/Recordings/RE00000000000000000000000000000001.wav',
        ];

        $rec = Recording::fromArray($payload);

        self::assertSame(
            'https://api.voiceml.example/Recordings/RE00000000000000000000000000000001.wav',
            $rec->mediaUrl,
        );
    }

    public function testRecordingFromArrayLeavesMediaUrlNullWhenAbsent(): void
    {
        $payload = [
            'sid' => 'RE00000000000000000000000000000001',
            'account_sid' => self::ACCOUNT_SID,
            'call_sid' => self::CALL_SID,
            'status' => 'in-progress',
            // media_url deliberately omitted
        ];

        $rec = Recording::fromArray($payload);

        self::assertNull($rec->mediaUrl);
    }

    public function testIncomingPhoneNumberFromArrayPopulatesTypeField(): void
    {
        $payload = [
            'sid' => self::PHONE_NUMBER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'phone_number' => '+18005551234',
            'api_version' => '2010-04-01',
            'uri' => '/uri',
            'type' => 'toll-free',
            'capabilities' => ['voice' => true, 'sms' => true, 'mms' => false, 'fax' => false],
        ];

        $ipn = IncomingPhoneNumber::fromArray($payload);

        self::assertSame('toll-free', $ipn->type);
    }

    // ---------------------------------------------------------------------
    // v0.6.3 additions — Participant coaching, Recording.error_code, list filters
    // ---------------------------------------------------------------------

    public function testParticipantFromArrayPopulatesCoachingFields(): void
    {
        $payload = [
            'call_sid' => self::CALL_SID,
            'conference_sid' => 'CF00000000000000000000000000000001',
            'account_sid' => self::ACCOUNT_SID,
            'muted' => false,
            'hold' => false,
            'coaching' => true,
            'call_sid_to_coach' => 'CA00000000000000000000000000000002',
            'queue_time' => '9',
            'start_conference_on_enter' => true,
            'end_conference_on_exit' => false,
            'status' => 'failed',
            'api_version' => '2010-04-01',
            'uri' => '/uri',
        ];

        $participant = Participant::fromArray($payload);

        self::assertTrue($participant->coaching);
        self::assertSame('CA00000000000000000000000000000002', $participant->callSidToCoach);
        self::assertSame('9', $participant->queueTime);
        self::assertSame('failed', $participant->status);
    }

    public function testRecordingFromArrayPopulatesErrorCode(): void
    {
        $payload = [
            'sid' => 'RE00000000000000000000000000000001',
            'account_sid' => self::ACCOUNT_SID,
            'call_sid' => self::CALL_SID,
            'status' => 'completed',
            'source' => 'StartConferenceRecordingAPI',
            'error_code' => 13227,
        ];

        $rec = Recording::fromArray($payload);

        self::assertSame('StartConferenceRecordingAPI', $rec->source);
        self::assertSame(13227, $rec->errorCode);
    }

    public function testListCallsParamsEmitsStartAndEndTimeWireNames(): void
    {
        $query = (new ListCallsParams(
            startTime: '2025-06-01',
            startTimeLt: '2025-06-15',
            startTimeGt: '2025-05-01',
            endTime: '2025-06-30',
            endTimeLt: '2025-07-01',
            endTimeGt: '2025-06-01',
        ))->toQuery();

        self::assertSame('2025-06-01', $query['StartTime']);
        self::assertSame('2025-06-15', $query['StartTime<']);
        self::assertSame('2025-05-01', $query['StartTime>']);
        self::assertSame('2025-06-30', $query['EndTime']);
        self::assertSame('2025-07-01', $query['EndTime<']);
        self::assertSame('2025-06-01', $query['EndTime>']);
    }

    public function testListCallsParamsEmitsPageToken(): void
    {
        $query = (new ListCallsParams(pageToken: 'cursor-abc123'))->toQuery();

        self::assertSame('cursor-abc123', $query['PageToken']);
    }

    public function testVersionIs064(): void
    {
        self::assertSame('0.6.4', Version::VERSION);
    }

    /**
     * Variant of {@see makeClient()} that lets us pass authToken: rather than apiKey:.
     *
     * @return array{client: Client, mock: MockHandler, history: array<int,array<string,mixed>>}
     */
    private function makeClientWithCreds(
        array $responses,
        ?string $apiKey,
        ?string $authToken,
        ?float $timeout = null,
        ?int $maxRetries = null,
    ): array {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        /** @var array<int,array<string,mixed>> $history */
        $history = [];
        $stack->push(Middleware::history($history));

        $guzzle = new GuzzleClient([
            'handler' => $stack,
            'http_errors' => false,
            'allow_redirects' => false,
        ]);

        $client = new Client(
            accountSid: self::ACCOUNT_SID,
            apiKey: $apiKey,
            timeout: $timeout,
            maxRetries: $maxRetries,
            httpClient: $guzzle,
            authToken: $authToken,
        );

        return ['client' => $client, 'mock' => $mock, 'history' => &$history];
    }
}
