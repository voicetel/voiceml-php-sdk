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
use VoiceML\Model\CreateCallRequest;
use VoiceML\Model\UpdateParticipantRequest;
use VoiceML\Resource\CallsResource;

final class SmokeTest extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const CALL_SID = 'CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

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
        self::assertStringContainsString(
            '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/Calls',
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
}
