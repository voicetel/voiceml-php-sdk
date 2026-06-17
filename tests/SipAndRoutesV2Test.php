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
use VoiceML\Resource\RoutesV2Resource;
use VoiceML\Resource\SipResource;
use VoiceML\Resource\SipDomainsResource;
use VoiceML\Resource\SipCredentialListsResource;
use VoiceML\Resource\SipIpAccessControlListsResource;

final class SipAndRoutesV2Test extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const DOMAIN_SID = 'SD11111111111111111111111111111111';
    private const CL_SID = 'CL22222222222222222222222222222222';
    private const CR_SID = 'CR33333333333333333333333333333333';
    private const ACL_SID = 'AL44444444444444444444444444444444';
    private const IP_SID = 'IP55555555555555555555555555555555';
    private const MAPPING_SID = 'CL99999999999999999999999999999999';
    private const DOMAIN_NAME = 'ingress.example.com';
    private const QQ_SID = 'QQ00000000000000000000000000000000';

    /**
     * @param list<Response> $responses
     * @return array{client: Client, history: array<int,array<string,mixed>>}
     */
    private function makeClient(array $responses): array
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $history = [];
        $stack->push(Middleware::history($history));
        $guzzle = new GuzzleClient(['handler' => $stack, 'http_errors' => false]);

        $client = new Client(
            accountSid: self::ACCOUNT_SID,
            apiKey: self::API_KEY,
            httpClient: $guzzle,
            maxRetries: 0,
        );
        return ['client' => $client, 'history' => &$history];
    }

    private function domainJson(): string
    {
        return json_encode([
            'sid' => self::DOMAIN_SID, 'account_sid' => self::ACCOUNT_SID,
            'domain_name' => self::DOMAIN_NAME, 'api_version' => '2010-04-01',
            'friendly_name' => 'ingress', 'secure' => true,
            'date_created' => 'Mon, 17 Jun 2026 12:00:00 +0000',
            'date_updated' => 'Mon, 17 Jun 2026 12:00:00 +0000',
            'uri' => '/2010-04-01/Accounts/' . self::ACCOUNT_SID . '/SIP/Domains/' . self::DOMAIN_SID . '.json',
        ], JSON_THROW_ON_ERROR);
    }

    private function rv2Json(): string
    {
        return json_encode([
            'sid' => self::QQ_SID, 'sip_domain' => self::DOMAIN_NAME,
            'account_sid' => self::ACCOUNT_SID, 'friendly_name' => 'ingress',
            'voice_region' => 'us1',
            'url' => 'https://voiceml.voicetel.com/v2/SipDomains/' . self::DOMAIN_NAME,
            'date_created' => '2026-06-17T20:00:00Z',
            'date_updated' => '2026-06-17T20:00:00Z',
        ], JSON_THROW_ON_ERROR);
    }

    private function mappingJson(): string
    {
        return json_encode([
            'sid' => self::MAPPING_SID, 'account_sid' => self::ACCOUNT_SID,
            'domain_sid' => self::DOMAIN_SID,
            'date_created' => 'x', 'date_updated' => 'x',
            'uri' => '/x',
        ], JSON_THROW_ON_ERROR);
    }

    public function testSipResourceIsWiredOnClient(): void
    {
        $bag = $this->makeClient([]);

        $client = $bag['client'];
        self::assertInstanceOf(SipResource::class, $client->sip);
        self::assertInstanceOf(SipDomainsResource::class, $client->sip->domains);
        self::assertInstanceOf(SipCredentialListsResource::class, $client->sip->credentialLists);
        self::assertInstanceOf(SipIpAccessControlListsResource::class, $client->sip->ipAccessControlLists);
        self::assertInstanceOf(RoutesV2Resource::class, $client->routesV2);
    }

    public function testSipDomainCreate(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], $this->domainJson()),
        ]);
        $client = $bag['client'];
        $d = $client->sip->domains->create([
            'DomainName' => self::DOMAIN_NAME,
            'VoiceUrl' => 'https://hooks/voice',
            'SipRegistration' => 'false',
            'Secure' => 'true',
        ]);
        self::assertSame(self::DOMAIN_SID, $d->sid);
        self::assertStringContainsString('/SIP/Domains.json',
            (string) $bag['history'][0]['request']->getUri());
    }

    public function testSipDomainsListFetchUpdateDelete(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], json_encode(['domains' => [json_decode($this->domainJson(), true)], 'page' => 0, 'page_size' => 50, 'total' => 1], JSON_THROW_ON_ERROR)),
            new Response(200, [], $this->domainJson()),
            new Response(200, [], $this->domainJson()),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];
        self::assertCount(1, $client->sip->domains->list()->domains);
        self::assertSame(self::DOMAIN_SID, $client->sip->domains->fetch(self::DOMAIN_SID)->sid);
        $client->sip->domains->update(self::DOMAIN_SID, ['FriendlyName' => 'renamed']);
        $client->sip->domains->delete(self::DOMAIN_SID);
        self::assertSame('DELETE', $bag['history'][3]['request']->getMethod());
    }

    public function testSipDomainAuthCallsCredentialListMappingRouting(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], $this->mappingJson()),
        ]);
        $client = $bag['client'];
        $client->sip->domains->createAuthCallsCredentialListMapping(self::DOMAIN_SID, self::CL_SID);
        self::assertStringContainsString('/Auth/Calls/CredentialListMappings.json',
            (string) $bag['history'][0]['request']->getUri());
    }

    public function testSipDomainAuthRegistrationsCredentialListMappingRouting(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], $this->mappingJson()),
        ]);
        $client = $bag['client'];
        $client->sip->domains->createAuthRegistrationsCredentialListMapping(self::DOMAIN_SID, self::CL_SID);
        self::assertStringContainsString('/Auth/Registrations/CredentialListMappings.json',
            (string) $bag['history'][0]['request']->getUri());
    }

    public function testSipCredentialListsCRUD(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], json_encode([
                'sid' => self::CL_SID, 'account_sid' => self::ACCOUNT_SID, 'friendly_name' => 'office-handsets',
                'date_created' => 'x', 'date_updated' => 'x', 'uri' => '/x',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $client = $bag['client'];
        $cl = $client->sip->credentialLists->create('office-handsets');
        self::assertSame(self::CL_SID, $cl->sid);
    }

    public function testSipCredentialsNestedCreate(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], json_encode([
                'sid' => self::CR_SID, 'account_sid' => self::ACCOUNT_SID,
                'credential_list_sid' => self::CL_SID, 'username' => 'alice',
                'date_created' => 'x', 'date_updated' => 'x', 'uri' => '/x',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $client = $bag['client'];
        $cr = $client->sip->credentialLists->createCredential(self::CL_SID, 'alice', 'hunter2');
        self::assertSame('alice', $cr->username);
        self::assertStringContainsString('/Credentials.json',
            (string) $bag['history'][0]['request']->getUri());
    }

    public function testSipIpAddressesNestedCreate(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], json_encode([
                'sid' => self::IP_SID, 'account_sid' => self::ACCOUNT_SID,
                'ip_access_control_list_sid' => self::ACL_SID,
                'friendly_name' => 'carrier-edge-1', 'ip_address' => '203.0.113.10',
                'cidr_prefix_length' => 32, 'date_created' => 'x', 'date_updated' => 'x', 'uri' => '/x',
            ], JSON_THROW_ON_ERROR)),
        ]);
        $client = $bag['client'];
        $ip = $client->sip->ipAccessControlLists->createIpAddress(self::ACL_SID, 'carrier-edge-1', '203.0.113.10', 32);
        self::assertSame(32, $ip->cidrPrefixLength);
    }

    public function testRoutesV2SipDomainsFetch(): void
    {
        $bag = $this->makeClient([
            new Response(200, ['Content-Type' => 'application/json'], $this->rv2Json()),
        ]);
        $client = $bag['client'];
        $rv = $client->routesV2->sipDomains->fetch(self::DOMAIN_NAME);
        self::assertSame(self::QQ_SID, $rv->sid);
        self::assertSame('us1', $rv->voiceRegion);
        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v2/SipDomains/' . self::DOMAIN_NAME, $uri);
        self::assertStringNotContainsString(self::ACCOUNT_SID, $uri);
    }

    public function testRoutesV2SipDomainsUpdate(): void
    {
        $bag = $this->makeClient([
            new Response(200, [], $this->rv2Json()),
        ]);
        $client = $bag['client'];
        $client->routesV2->sipDomains->update(self::DOMAIN_NAME, voiceRegion: 'ie1', friendlyName: 'renamed');
        self::assertSame('POST', $bag['history'][0]['request']->getMethod());
        $body = (string) $bag['history'][0]['request']->getBody();
        self::assertStringContainsString('VoiceRegion=ie1', $body);
        self::assertStringContainsString('FriendlyName=renamed', $body);
    }
}
