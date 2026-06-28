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
use VoiceML\Model\ConversationsV1Conversation;
use VoiceML\Model\CreateConversationsV1ConversationMessageRequest;
use VoiceML\Model\CreateConversationsV1ConversationRequest;
use VoiceML\Model\CreateConversationsV1RoleRequest;
use VoiceML\Model\CreateConversationsV1ServiceRequest;
use VoiceML\Model\CreateConversationsV1UserRequest;
use VoiceML\Model\CreateVoiceV1ConnectionPolicyTargetRequest;
use VoiceML\Model\CreateVoiceV1IpRecordRequest;
use VoiceML\Model\CreateVoiceV1SourceIpMappingRequest;
use VoiceML\Model\UpdateConversationsV1ConfigurationRequest;
use VoiceML\Model\UpdateConversationsV1ConfigurationWebhookRequest;
use VoiceML\Model\UpdateConversationsV1UserConversationRequest;
use VoiceML\Model\UpdateRoutesV2PhoneNumberRequest;
use VoiceML\Model\UpdateVoiceV1DialingPermissionsSettingsRequest;
use VoiceML\Resource\ConversationsV1Resource;
use VoiceML\Resource\RoutesV2PhoneNumbersResource;
use VoiceML\Resource\VoiceV1Resource;

/**
 * Wire-shape tests for the v0.9.0 surface: Twilio Conversations v1 + Voice v1
 * families and the routes/v2 PhoneNumber addition. Mock-Guzzle-backed; the goal
 * is to catch URL, HTTP-method, and form-encoding regressions before they
 * reach the live API.
 */
final class V090Test extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const CONVERSATION_SID = 'CH00000000000000000000000000000001';
    private const MESSAGE_SID = 'IM00000000000000000000000000000001';
    private const RECEIPT_SID = 'DY00000000000000000000000000000001';
    private const PARTICIPANT_SID = 'MB00000000000000000000000000000001';
    private const WEBHOOK_SID = 'WH00000000000000000000000000000001';
    private const ROLE_SID = 'RL00000000000000000000000000000001';
    private const USER_SID = 'US00000000000000000000000000000001';
    private const CRED_SID = 'CR00000000000000000000000000000001';
    private const ADDRESS_SID = 'IG00000000000000000000000000000001';
    private const SERVICE_SID = 'IS00000000000000000000000000000001';
    private const IP_RECORD_SID = 'IL00000000000000000000000000000001';
    private const SOURCE_MAP_SID = 'IB00000000000000000000000000000001';
    private const BYOC_SID = 'BY00000000000000000000000000000001';
    private const POLICY_SID = 'NY00000000000000000000000000000001';
    private const TARGET_SID = 'NE00000000000000000000000000000001';
    private const SIP_DOMAIN_SID = 'SD00000000000000000000000000000001';
    private const PHONE_NUMBER = '+18005551234';
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

    /** @param array<string,mixed> $extra */
    private function jsonResponse(array $extra, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($extra, JSON_THROW_ON_ERROR));
    }

    public function testV090ResourcesWiredOnClient(): void
    {
        $bag = $this->makeClient([]);
        $client = $bag['client'];
        self::assertInstanceOf(VoiceV1Resource::class, $client->voiceV1);
        self::assertInstanceOf(ConversationsV1Resource::class, $client->conversationsV1);
        self::assertInstanceOf(RoutesV2PhoneNumbersResource::class, $client->routesV2->phoneNumbers);
    }

    // -----------------------------------------------------------------------
    // routes/v2 PhoneNumbers
    // -----------------------------------------------------------------------

    public function testRoutesV2PhoneNumbersFetchAndUpdate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'sid' => self::QQ_SID,
                'phone_number' => self::PHONE_NUMBER,
                'account_sid' => self::ACCOUNT_SID,
                'voice_region' => 'us1',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
            ]),
            $this->jsonResponse([
                'sid' => self::QQ_SID,
                'phone_number' => self::PHONE_NUMBER,
                'account_sid' => self::ACCOUNT_SID,
                'voice_region' => 'ie1',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
            ]),
        ]);
        $client = $bag['client'];

        $rv = $client->routesV2->phoneNumbers->fetch(self::PHONE_NUMBER);
        self::assertSame(self::QQ_SID, $rv->sid);
        self::assertSame('us1', $rv->voiceRegion);
        $fetchUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v2/PhoneNumbers/' . self::PHONE_NUMBER, $fetchUri);
        self::assertStringNotContainsString(self::ACCOUNT_SID, $fetchUri);

        $rv2 = $client->routesV2->phoneNumbers->update(
            self::PHONE_NUMBER,
            new UpdateRoutesV2PhoneNumberRequest(voiceRegion: 'ie1', friendlyName: 'renamed'),
        );
        self::assertSame('ie1', $rv2->voiceRegion);
        self::assertSame('POST', $bag['history'][1]['request']->getMethod());
        $body = (string) $bag['history'][1]['request']->getBody();
        self::assertStringContainsString('VoiceRegion=ie1', $body);
        self::assertStringContainsString('FriendlyName=renamed', $body);
    }

    // -----------------------------------------------------------------------
    // Voice v1 — IpRecords / SourceIpMappings / ByocTrunks / ConnectionPolicies / Settings
    // -----------------------------------------------------------------------

    public function testVoiceV1IpRecordsCRUD(): void
    {
        $ipJson = [
            'account_sid' => self::ACCOUNT_SID,
            'sid' => self::IP_RECORD_SID,
            'ip_address' => '203.0.113.10',
            'cidr_prefix_length' => 32,
            'friendly_name' => 'carrier-a',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'https://voice.twilio.com/v1/IpRecords/' . self::IP_RECORD_SID,
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($ipJson, 201),
            $this->jsonResponse(['ip_records' => [$ipJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($ipJson),
            $this->jsonResponse($ipJson),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $created = $client->voiceV1->ipRecords->create(new CreateVoiceV1IpRecordRequest(
            ipAddress: '203.0.113.10',
            friendlyName: 'carrier-a',
        ));
        self::assertSame(self::IP_RECORD_SID, $created->sid);
        self::assertSame(32, $created->cidrPrefixLength);
        self::assertSame('POST', $bag['history'][0]['request']->getMethod());
        self::assertStringContainsString('/v1/IpRecords', (string) $bag['history'][0]['request']->getUri());
        self::assertStringContainsString('IpAddress=203.0.113.10', (string) $bag['history'][0]['request']->getBody());

        self::assertCount(1, $client->voiceV1->ipRecords->list()->ipRecords);
        self::assertSame(self::IP_RECORD_SID, $client->voiceV1->ipRecords->fetch(self::IP_RECORD_SID)->sid);
        $client->voiceV1->ipRecords->update(self::IP_RECORD_SID, ['FriendlyName' => 'renamed']);
        $client->voiceV1->ipRecords->delete(self::IP_RECORD_SID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
    }

    public function testVoiceV1SourceIpMappingsCreate(): void
    {
        $json = [
            'sid' => self::SOURCE_MAP_SID,
            'ip_record_sid' => self::IP_RECORD_SID,
            'sip_domain_sid' => self::SIP_DOMAIN_SID,
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'https://voice.twilio.com/v1/SourceIpMappings/' . self::SOURCE_MAP_SID,
        ];
        $bag = $this->makeClient([$this->jsonResponse($json, 201)]);
        $client = $bag['client'];

        $m = $client->voiceV1->sourceIpMappings->create(new CreateVoiceV1SourceIpMappingRequest(
            ipRecordSid: self::IP_RECORD_SID,
            sipDomainSid: self::SIP_DOMAIN_SID,
        ));
        self::assertSame(self::SOURCE_MAP_SID, $m->sid);
        $body = (string) $bag['history'][0]['request']->getBody();
        self::assertStringContainsString('IpRecordSid=' . self::IP_RECORD_SID, $body);
        self::assertStringContainsString('SipDomainSid=' . self::SIP_DOMAIN_SID, $body);
    }

    public function testVoiceV1ByocTrunksRouting(): void
    {
        $json = [
            'account_sid' => self::ACCOUNT_SID,
            'sid' => self::BYOC_SID,
            'friendly_name' => 'carrier-x',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'https://voice.twilio.com/v1/ByocTrunks/' . self::BYOC_SID,
        ];
        $bag = $this->makeClient([$this->jsonResponse($json, 201)]);
        $client = $bag['client'];
        $t = $client->voiceV1->byocTrunks->create(['FriendlyName' => 'carrier-x']);
        self::assertSame(self::BYOC_SID, $t->sid);
        self::assertStringContainsString('/v1/ByocTrunks', (string) $bag['history'][0]['request']->getUri());
    }

    public function testVoiceV1ConnectionPolicyTargetsNestedRouting(): void
    {
        $targetJson = [
            'account_sid' => self::ACCOUNT_SID,
            'connection_policy_sid' => self::POLICY_SID,
            'sid' => self::TARGET_SID,
            'target' => 'sip:edge@example.com',
            'priority' => 10,
            'weight' => 10,
            'enabled' => true,
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([$this->jsonResponse($targetJson, 201)]);
        $client = $bag['client'];

        $t = $client->voiceV1->connectionPolicies
            ->targets(self::POLICY_SID)
            ->create(new CreateVoiceV1ConnectionPolicyTargetRequest(target: 'sip:edge@example.com'));
        self::assertSame(self::TARGET_SID, $t->sid);
        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/ConnectionPolicies/' . self::POLICY_SID . '/Targets', $uri);
        self::assertStringNotContainsString(self::ACCOUNT_SID, $uri);
    }

    public function testVoiceV1SettingsFetchAndUpdate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'dialing_permissions_inheritance' => false,
                'url' => 'https://voice.twilio.com/v1/Settings',
            ]),
            $this->jsonResponse([
                'dialing_permissions_inheritance' => true,
                'url' => 'https://voice.twilio.com/v1/Settings',
            ], 202),
        ]);
        $client = $bag['client'];
        $s = $client->voiceV1->settings->fetch();
        self::assertFalse($s->dialingPermissionsInheritance);
        $u = $client->voiceV1->settings->update(new UpdateVoiceV1DialingPermissionsSettingsRequest(dialingPermissionsInheritance: true));
        self::assertTrue($u->dialingPermissionsInheritance);
        self::assertStringContainsString('DialingPermissionsInheritance=true', (string) $bag['history'][1]['request']->getBody());
    }

    // -----------------------------------------------------------------------
    // Conversations v1 — top-level Conversation + nested Messages, Receipts, Participants, Webhooks
    // -----------------------------------------------------------------------

    public function testConversationsV1ConversationCreateAndList(): void
    {
        $convJson = [
            'account_sid' => self::ACCOUNT_SID,
            'sid' => self::CONVERSATION_SID,
            'state' => 'active',
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'https://conversations.twilio.com/v1/Conversations/' . self::CONVERSATION_SID,
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($convJson, 201),
            $this->jsonResponse(['conversations' => [$convJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
        ]);
        $client = $bag['client'];

        $conv = $client->conversationsV1->conversations->create(new CreateConversationsV1ConversationRequest(
            friendlyName: 'support',
            state: 'active',
            timersInactive: 'PT1H',
        ));
        self::assertInstanceOf(ConversationsV1Conversation::class, $conv);
        self::assertSame('active', $conv->state);
        $body = (string) $bag['history'][0]['request']->getBody();
        self::assertStringContainsString('FriendlyName=support', $body);
        self::assertStringContainsString('Timers.Inactive=PT1H', urldecode($body));
        self::assertStringContainsString('/v1/Conversations', (string) $bag['history'][0]['request']->getUri());

        $list = $client->conversationsV1->conversations->list(['PageSize' => 25]);
        self::assertCount(1, $list->conversations);
        self::assertStringContainsString('PageSize=25', (string) $bag['history'][1]['request']->getUri());
    }

    public function testConversationsV1MessagesNestedRouting(): void
    {
        $msgJson = [
            'account_sid' => self::ACCOUNT_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::MESSAGE_SID,
            'index' => 0,
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([$this->jsonResponse($msgJson, 201)]);
        $client = $bag['client'];

        $m = $client->conversationsV1
            ->conversations
            ->messages(self::CONVERSATION_SID)
            ->create(new CreateConversationsV1ConversationMessageRequest(author: '+15551234567', body: 'Hello'));
        self::assertSame(self::MESSAGE_SID, $m->sid);
        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Conversations/' . self::CONVERSATION_SID . '/Messages', $uri);
        self::assertStringNotContainsString(self::ACCOUNT_SID, $uri);
    }

    public function testConversationsV1ReceiptsDoubleNestedRouting(): void
    {
        $receiptJson = [
            'account_sid' => self::ACCOUNT_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::RECEIPT_SID,
            'message_sid' => self::MESSAGE_SID,
            'status' => 'delivered',
            'error_code' => 0,
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([$this->jsonResponse($receiptJson)]);
        $client = $bag['client'];

        $r = $client->conversationsV1
            ->conversations
            ->messages(self::CONVERSATION_SID)
            ->receipts(self::MESSAGE_SID)
            ->fetch(self::RECEIPT_SID);
        self::assertSame(self::RECEIPT_SID, $r->sid);
        self::assertSame('delivered', $r->status);
        self::assertStringContainsString(
            '/v1/Conversations/' . self::CONVERSATION_SID . '/Messages/' . self::MESSAGE_SID . '/Receipts/' . self::RECEIPT_SID,
            (string) $bag['history'][0]['request']->getUri(),
        );
    }

    public function testConversationsV1ParticipantsAndWebhooks(): void
    {
        $partJson = [
            'account_sid' => self::ACCOUNT_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::PARTICIPANT_SID,
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $whJson = [
            'sid' => self::WEBHOOK_SID,
            'account_sid' => self::ACCOUNT_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'target' => 'webhook',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($partJson, 201),
            $this->jsonResponse($whJson, 201),
        ]);
        $client = $bag['client'];

        $p = $client->conversationsV1
            ->conversations
            ->participants(self::CONVERSATION_SID)
            ->create(['Identity' => 'alice']);
        self::assertSame(self::PARTICIPANT_SID, $p->sid);
        self::assertStringContainsString(
            '/v1/Conversations/' . self::CONVERSATION_SID . '/Participants',
            (string) $bag['history'][0]['request']->getUri(),
        );

        $w = $client->conversationsV1
            ->conversations
            ->webhooks(self::CONVERSATION_SID)
            ->create(['Target' => 'webhook', 'Configuration.Url' => 'https://hooks/wh']);
        self::assertSame(self::WEBHOOK_SID, $w->sid);
        $whUri = (string) $bag['history'][1]['request']->getUri();
        self::assertStringContainsString(
            '/v1/Conversations/' . self::CONVERSATION_SID . '/Webhooks',
            $whUri,
        );
        $whBody = urldecode((string) $bag['history'][1]['request']->getBody());
        self::assertStringContainsString('Configuration.Url=https://hooks/wh', $whBody);
    }

    // -----------------------------------------------------------------------
    // Conversations v1 — Roles, Users, UserConversations, Credentials
    // -----------------------------------------------------------------------

    public function testConversationsV1RolesCreate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'sid' => self::ROLE_SID,
                'account_sid' => self::ACCOUNT_SID,
                'type' => 'conversation',
                'friendly_name' => 'admin',
                'permissions' => ['sendMessage', 'editMessage'],
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
                'url' => 'x',
            ], 201),
        ]);
        $client = $bag['client'];
        $r = $client->conversationsV1->roles->create(new CreateConversationsV1RoleRequest(
            friendlyName: 'admin',
            type: 'conversation',
            permission: ['sendMessage', 'editMessage'],
        ));
        self::assertSame(self::ROLE_SID, $r->sid);
        self::assertSame(['sendMessage', 'editMessage'], $r->permissions);
        $body = urldecode((string) $bag['history'][0]['request']->getBody());
        // Repeated form field encoding from Guzzle's form_params (PHP arrays):
        self::assertStringContainsString('Permission', $body);
        self::assertStringContainsString('sendMessage', $body);
    }

    public function testConversationsV1UsersAndUserConversations(): void
    {
        $userJson = [
            'sid' => self::USER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'identity' => 'alice',
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $userConvJson = [
            'account_sid' => self::ACCOUNT_SID,
            'conversation_state' => 'active',
            'notification_level' => 'default',
            'conversation_sid' => self::CONVERSATION_SID,
            'user_sid' => self::USER_SID,
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($userJson, 201),
            $this->jsonResponse($userConvJson),
        ]);
        $client = $bag['client'];

        $u = $client->conversationsV1->users->create(new CreateConversationsV1UserRequest(
            identity: 'alice',
            friendlyName: 'Alice',
        ));
        self::assertSame('alice', $u->identity);

        $uc = $client->conversationsV1->users
            ->conversations(self::USER_SID)
            ->update(self::CONVERSATION_SID, new UpdateConversationsV1UserConversationRequest(notificationLevel: 'muted'));
        self::assertSame('default', $uc->notificationLevel);
        self::assertStringContainsString(
            '/v1/Users/' . self::USER_SID . '/Conversations/' . self::CONVERSATION_SID,
            (string) $bag['history'][1]['request']->getUri(),
        );
    }

    public function testConversationsV1CredentialsRouting(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'sid' => self::CRED_SID,
                'account_sid' => self::ACCOUNT_SID,
                'type' => 'apn',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
                'url' => 'x',
            ], 201),
        ]);
        $client = $bag['client'];
        $c = $client->conversationsV1->credentials->create(['Type' => 'apn']);
        self::assertSame('apn', $c->type);
        self::assertStringContainsString('/v1/Credentials', (string) $bag['history'][0]['request']->getUri());
    }

    // -----------------------------------------------------------------------
    // Conversations v1 — Configuration + Webhooks + Addresses
    // -----------------------------------------------------------------------

    public function testConversationsV1ConfigurationFetchUpdate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'default_inactive_timer' => 'PT1H',
                'url' => 'https://conversations.twilio.com/v1/Configuration',
            ]),
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'default_inactive_timer' => 'PT30M',
                'url' => 'https://conversations.twilio.com/v1/Configuration',
            ]),
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'method' => 'POST',
                'target' => 'webhook',
                'url' => 'https://conversations.twilio.com/v1/Configuration/Webhooks',
            ]),
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'method' => 'POST',
                'target' => 'webhook',
                'pre_webhook_url' => 'https://pre',
                'url' => 'https://conversations.twilio.com/v1/Configuration/Webhooks',
            ]),
            $this->jsonResponse([
                'sid' => self::ADDRESS_SID,
                'account_sid' => self::ACCOUNT_SID,
                'type' => 'sms',
                'address' => '+15551234567',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
                'url' => 'x',
            ], 201),
        ]);
        $client = $bag['client'];

        $cfg = $client->conversationsV1->configuration->fetch();
        self::assertSame('PT1H', $cfg->defaultInactiveTimer);
        self::assertStringContainsString('/v1/Configuration', (string) $bag['history'][0]['request']->getUri());

        $upd = $client->conversationsV1->configuration->update(new UpdateConversationsV1ConfigurationRequest(defaultInactiveTimer: 'PT30M'));
        self::assertSame('PT30M', $upd->defaultInactiveTimer);

        $wh = $client->conversationsV1->configuration->webhooks->fetch();
        self::assertSame('webhook', $wh->target);
        self::assertStringContainsString('/v1/Configuration/Webhooks', (string) $bag['history'][2]['request']->getUri());

        $whUpd = $client->conversationsV1->configuration->webhooks->update(new UpdateConversationsV1ConfigurationWebhookRequest(preWebhookUrl: 'https://pre'));
        self::assertSame('https://pre', $whUpd->preWebhookUrl);

        $addr = $client->conversationsV1->configuration->addresses->create(['Type' => 'sms', 'Address' => '+15551234567']);
        self::assertSame(self::ADDRESS_SID, $addr->sid);
        self::assertStringContainsString('/v1/Configuration/Addresses', (string) $bag['history'][4]['request']->getUri());
    }

    // -----------------------------------------------------------------------
    // Conversations v1 — ParticipantConversations, ConversationWithParticipants, Services
    // -----------------------------------------------------------------------

    public function testConversationsV1ParticipantConversationsList(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'conversations' => [[
                    'account_sid' => self::ACCOUNT_SID,
                    'conversation_state' => 'active',
                    'conversation_sid' => self::CONVERSATION_SID,
                    'conversation_date_created' => '2026-06-27T12:00:00Z',
                    'conversation_date_updated' => '2026-06-27T12:00:00Z',
                ]],
                'meta' => ['page' => 0, 'page_size' => 50],
            ]),
        ]);
        $client = $bag['client'];
        $list = $client->conversationsV1->participantConversations->list(['Identity' => 'alice']);
        self::assertCount(1, $list->conversations);
        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/ParticipantConversations', $uri);
        self::assertStringContainsString('Identity=alice', $uri);
    }

    public function testConversationsV1ConversationWithParticipantsCreate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'sid' => self::CONVERSATION_SID,
                'state' => 'active',
                'attributes' => '{}',
                'friendly_name' => 'multi',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
                'url' => 'x',
            ], 201),
        ]);
        $client = $bag['client'];
        $c = $client->conversationsV1->conversationWithParticipants->create([
            'FriendlyName' => 'multi',
            'Participant' => ['{"identity":"alice"}', '{"identity":"bob"}'],
        ]);
        self::assertSame(self::CONVERSATION_SID, $c->sid);
        self::assertStringContainsString('/v1/ConversationWithParticipants', (string) $bag['history'][0]['request']->getUri());
    }

    public function testConversationsV1ServicesCreate(): void
    {
        $bag = $this->makeClient([
            $this->jsonResponse([
                'sid' => self::SERVICE_SID,
                'account_sid' => self::ACCOUNT_SID,
                'friendly_name' => 'sandbox',
                'date_created' => '2026-06-27T12:00:00Z',
                'date_updated' => '2026-06-27T12:00:00Z',
                'url' => 'x',
            ], 201),
        ]);
        $client = $bag['client'];
        $s = $client->conversationsV1->services->create(new CreateConversationsV1ServiceRequest(friendlyName: 'sandbox'));
        self::assertSame(self::SERVICE_SID, $s->sid);
        self::assertStringContainsString('/v1/Services', (string) $bag['history'][0]['request']->getUri());
    }
}
