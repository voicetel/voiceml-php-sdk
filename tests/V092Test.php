<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use VoiceML\Client;
use VoiceML\Hosts;
use VoiceML\Model\CreateMessagingServiceRequest;
use VoiceML\Model\MessagingService;
use VoiceML\Model\UpdateMessagingServiceRequest;
use VoiceML\Resource\MessagingV1Resource;
use VoiceML\Resource\PricingResource;

/**
 * Wire-shape tests for the v0.9.2 surface: per-product host routing, Messaging
 * Service (#16), and Pricing v1/v2 (#18).
 *
 * Messaging Service must ride `messaging.voicetel.com` (that host is what
 * disambiguates it from Conversation Service on the shared `/v1/Services`
 * path). Pricing rides the default host. Host derivation is unit-tested
 * directly.
 */
final class V092Test extends TestCase
{
    private const ACCOUNT_SID = 'AC' . '00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const BASE = 'https://voiceml.voicetel.com';
    private const MSG = 'https://messaging.voicetel.com';
    private const CONV = 'https://conversations.voicetel.com';

    /**
     * @param list<Response> $responses
     * @return array{client: Client, history: array<int,array<string,mixed>>}
     */
    private function makeClient(array $responses, ?string $baseUrl = null, ?string $messagingBaseUrl = null): array
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $history = [];
        $stack->push(Middleware::history($history));
        $guzzle = new GuzzleClient(['handler' => $stack, 'http_errors' => false]);

        $client = new Client(
            accountSid: self::ACCOUNT_SID,
            apiKey: self::API_KEY,
            baseUrl: $baseUrl,
            httpClient: $guzzle,
            maxRetries: 0,
            messagingBaseUrl: $messagingBaseUrl,
        );
        return ['client' => $client, 'history' => &$history];
    }

    /** @param array<string,mixed> $extra */
    private function jsonResponse(array $extra, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($extra, JSON_THROW_ON_ERROR));
    }

    /** @param array<string,mixed> $overrides */
    private function messagingServicePayload(string $sid, array $overrides = []): array
    {
        return $overrides + [
            'sid' => $sid,
            'account_sid' => self::ACCOUNT_SID,
            'friendly_name' => 'alerts',
            'inbound_request_url' => 'https://example.com/in',
            'sticky_sender' => true,
            'date_created' => '2026-07-08T00:00:00Z',
            'date_updated' => '2026-07-08T00:00:00Z',
            'url' => self::MSG . '/v1/Services/' . $sid,
        ];
    }

    private function meta(): array
    {
        return [
            'first_page_url' => self::MSG . '/v1/Services?Page=0',
            'next_page_url' => null,
            'previous_page_url' => null,
            'url' => self::MSG . '/v1/Services',
            'page' => 0,
            'page_size' => 50,
            'key' => 'services',
        ];
    }

    // -----------------------------------------------------------------------
    // Host resolution
    // -----------------------------------------------------------------------

    public function testHostDerivationFromDefault(): void
    {
        [$default, $messaging, $conversations] = Hosts::resolveProductBaseUrls(self::BASE);
        self::assertSame(self::BASE, $default);
        self::assertSame(self::MSG, $messaging);
        self::assertSame(self::CONV, $conversations);
    }

    public function testHostDerivationRegional(): void
    {
        [$default, $messaging, $conversations] = Hosts::resolveProductBaseUrls(
            'https://east-1.us.voiceml.voicetel.com',
        );
        self::assertSame('https://east-1.us.voiceml.voicetel.com', $default);
        self::assertSame('https://east-1.us.messaging.voicetel.com', $messaging);
        self::assertSame('https://east-1.us.conversations.voicetel.com', $conversations);
    }

    public function testHostDerivationSelfHostedFallsBackToSingleHost(): void
    {
        // A custom host has no `voiceml` label to swap — every product stays on
        // it, so a single-host deployment keeps working.
        [$default, $messaging, $conversations] = Hosts::resolveProductBaseUrls('https://pbx.acme.com');
        self::assertSame('https://pbx.acme.com', $default);
        self::assertSame('https://pbx.acme.com', $messaging);
        self::assertSame('https://pbx.acme.com', $conversations);
    }

    public function testHostDerivationExplicitOverridesWin(): void
    {
        [$default, $messaging, $conversations] = Hosts::resolveProductBaseUrls(
            'https://pbx.acme.com',
            'https://msg.acme.com',
            'https://conv.acme.com/',
        );
        self::assertSame('https://pbx.acme.com', $default);
        self::assertSame('https://msg.acme.com', $messaging);
        self::assertSame('https://conv.acme.com', $conversations);
    }

    public function testV092ResourcesWired(): void
    {
        $client = $this->makeClient([])['client'];
        self::assertInstanceOf(MessagingV1Resource::class, $client->messagingV1);
        self::assertInstanceOf(PricingResource::class, $client->pricing);
        // Deep wiring: every pricing product group resolves.
        self::assertNotNull($client->pricing->v1->voice->countries);
        self::assertNotNull($client->pricing->v1->voice->numbers);
        self::assertNotNull($client->pricing->v1->messaging->countries);
        self::assertNotNull($client->pricing->v1->phoneNumbers->countries);
        self::assertNotNull($client->pricing->v2->voice->countries);
        self::assertNotNull($client->pricing->v2->voice->numbers);
        self::assertNotNull($client->pricing->v2->trunking->countries);
        self::assertNotNull($client->pricing->v2->trunking->numbers);
    }

    // -----------------------------------------------------------------------
    // Messaging Service — CRUD on the messaging host
    // -----------------------------------------------------------------------

    public function testMessagingServiceCrudOnMessagingHost(): void
    {
        $sid = 'MG' . '11111111111111111111111111111111';
        $bag = $this->makeClient([
            $this->jsonResponse($this->messagingServicePayload($sid), 201),
            $this->jsonResponse(['services' => [$this->messagingServicePayload($sid)], 'meta' => $this->meta()]),
            $this->jsonResponse($this->messagingServicePayload($sid)),
            $this->jsonResponse($this->messagingServicePayload($sid, ['friendly_name' => 'renamed'])),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $created = $client->messagingV1->services->create(new CreateMessagingServiceRequest(
            friendlyName: 'alerts',
            inboundRequestUrl: 'https://example.com/in',
            stickySender: true,
        ));
        self::assertInstanceOf(MessagingService::class, $created);
        self::assertSame($sid, $created->sid);
        self::assertStringStartsWith('MG', (string) $created->sid);
        self::assertTrue($created->stickySender);

        $listed = $client->messagingV1->services->list(['PageSize' => 25]);
        self::assertCount(1, $listed->services);

        $fetched = $client->messagingV1->services->fetch($sid);
        self::assertSame($sid, $fetched->sid);

        $updated = $client->messagingV1->services->update($sid, new UpdateMessagingServiceRequest(friendlyName: 'renamed'));
        self::assertSame('renamed', $updated->friendlyName);

        $client->messagingV1->services->delete($sid);

        // Every request must have hit the messaging host, not the default one.
        foreach ($bag['history'] as $entry) {
            self::assertSame('messaging.voicetel.com', $entry['request']->getUri()->getHost());
        }

        // Create body.
        parse_str((string) $bag['history'][0]['request']->getBody(), $createBody);
        self::assertSame('alerts', $createBody['FriendlyName']);
        self::assertSame('https://example.com/in', $createBody['InboundRequestUrl']);
        self::assertSame('true', $createBody['StickySender']);
        self::assertSame('POST', $bag['history'][0]['request']->getMethod());
        self::assertStringEndsWith('/v1/Services', $bag['history'][0]['request']->getUri()->getPath());

        // List query.
        self::assertStringContainsString('PageSize=25', (string) $bag['history'][1]['request']->getUri());

        // Fetch + update paths.
        self::assertStringEndsWith('/v1/Services/' . $sid, $bag['history'][2]['request']->getUri()->getPath());
        self::assertSame('POST', $bag['history'][3]['request']->getMethod());
        parse_str((string) $bag['history'][3]['request']->getBody(), $updateBody);
        self::assertSame(['FriendlyName' => 'renamed'], $updateBody);

        // Delete.
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
    }

    public function testMessagingServiceHostOverride(): void
    {
        $bag = $this->makeClient(
            [$this->jsonResponse(['services' => [], 'meta' => $this->meta()])],
            baseUrl: 'https://pbx.acme.com',
            messagingBaseUrl: 'https://msg.acme.com',
        );
        $bag['client']->messagingV1->services->list();
        self::assertSame('msg.acme.com', $bag['history'][0]['request']->getUri()->getHost());
    }

    // -----------------------------------------------------------------------
    // Pricing v1/v2 — read-only on the default host
    // -----------------------------------------------------------------------

    public function testPricingV1VoiceCountriesAndNumber(): void
    {
        $countries = [
            'countries' => [[
                'country' => 'United States',
                'iso_country' => 'US',
                'url' => self::BASE . '/v1/Voice/Countries/US',
            ]],
            'meta' => ['page' => 0, 'page_size' => 50],
        ];
        $country = [
            'country' => 'United States',
            'iso_country' => 'US',
            'outbound_prefix_prices' => [[
                'prefixes' => ['1'],
                'base_price' => '0.013',
                'current_price' => '0.013',
                'friendly_name' => 'United States & Canada',
            ]],
            'inbound_call_prices' => [[
                'base_price' => '0.0085',
                'current_price' => '0.0085',
                'number_type' => 'local',
            ]],
            'price_unit' => 'USD',
            'url' => self::BASE . '/v1/Voice/Countries/US',
        ];
        $number = [
            'number' => '+18005551234',
            'country' => 'United States',
            'iso_country' => 'US',
            'outbound_call_price' => ['base_price' => '0.013', 'current_price' => '0.013'],
            'inbound_call_price' => [
                'base_price' => '0.0085',
                'current_price' => '0.0085',
                'number_type' => 'toll free',
            ],
            'price_unit' => 'USD',
            'url' => self::BASE . '/v1/Voice/Numbers/+18005551234',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($countries),
            $this->jsonResponse($country),
            $this->jsonResponse($number),
        ]);
        $client = $bag['client'];

        $listed = $client->pricing->v1->voice->countries->list();
        self::assertSame('US', $listed->countries[0]->isoCountry);

        $fetched = $client->pricing->v1->voice->countries->fetch('US');
        self::assertInstanceOf(\VoiceML\Model\PricingVoiceCountry::class, $fetched);
        self::assertSame(['1'], $fetched->outboundPrefixPrices[0]->prefixes);

        $num = $client->pricing->v1->voice->numbers->fetch('+18005551234');
        self::assertSame('toll free', $num->inboundCallPrice?->numberType);

        foreach ($bag['history'] as $entry) {
            self::assertSame('voiceml.voicetel.com', $entry['request']->getUri()->getHost());
        }
        // E.164 `+` is percent-encoded in the number path segment.
        self::assertStringContainsString('%2B18005551234', (string) $bag['history'][2]['request']->getUri());
    }

    public function testPricingV2VoiceNumberWithOrigination(): void
    {
        $payload = [
            'destination_number' => '+18005551234',
            'origination_number' => '+15551112222',
            'country' => 'United States',
            'iso_country' => 'US',
            'outbound_call_prices' => [[
                'origination_prefixes' => ['1'],
                'base_price' => '0.013',
                'current_price' => '0.013',
            ]],
            'inbound_call_price' => [
                'base_price' => '0.0085',
                'current_price' => '0.0085',
                'number_type' => 'local',
            ],
            'price_unit' => 'USD',
            'url' => self::BASE . '/v2/Voice/Numbers/+18005551234',
        ];
        $bag = $this->makeClient([$this->jsonResponse($payload)]);

        $got = $bag['client']->pricing->v2->voice->numbers->fetch('+18005551234', '+15551112222');
        self::assertSame('+15551112222', $got->originationNumber);
        self::assertSame(['1'], $got->outboundCallPrices[0]->originationPrefixes);

        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v2/Voice/Numbers/%2B18005551234', $uri);
        self::assertStringContainsString('OriginationNumber=%2B15551112222', $uri);
    }

    public function testPricingV2TrunkingCountry(): void
    {
        $payload = [
            'country' => 'United States',
            'iso_country' => 'US',
            'terminating_prefix_prices' => [[
                'origination_prefixes' => ['1'],
                'destination_prefixes' => ['1'],
                'base_price' => '0.013',
                'current_price' => '0.013',
                'friendly_name' => 'US',
            ]],
            'originating_call_prices' => [[
                'base_price' => '0.0085',
                'current_price' => '0.0085',
                'number_type' => 'local',
            ]],
            'price_unit' => 'USD',
            'url' => self::BASE . '/v2/Trunking/Countries/US',
        ];
        $bag = $this->makeClient([$this->jsonResponse($payload)]);

        $got = $bag['client']->pricing->v2->trunking->countries->fetch('US');
        self::assertInstanceOf(\VoiceML\Model\PricingTrunkingCountry::class, $got);
        self::assertSame('US', $got->terminatingPrefixPrices[0]->friendlyName);
        self::assertSame('voiceml.voicetel.com', $bag['history'][0]['request']->getUri()->getHost());
    }

    public function testPricingV1MessagingCountriesList(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse(['countries' => [], 'meta' => ['page' => 0]]),
        ]);
        $listed = $bag['client']->pricing->v1->messaging->countries->list();
        self::assertSame([], $listed->countries);
        self::assertSame('voiceml.voicetel.com', $bag['history'][0]['request']->getUri()->getHost());
        self::assertStringEndsWith('/v1/Messaging/Countries', $bag['history'][0]['request']->getUri()->getPath());
    }
}
