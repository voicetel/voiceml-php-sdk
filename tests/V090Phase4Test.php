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
use VoiceML\Model\CreateConversationsV1ServiceConversationMessageRequest;
use VoiceML\Model\CreateConversationsV1ServiceConversationParticipantRequest;
use VoiceML\Model\CreateConversationsV1ServiceConversationRequest;
use VoiceML\Model\CreateConversationsV1ServiceConversationScopedWebhookRequest;
use VoiceML\Model\CreateConversationsV1ServiceConversationWithParticipantsRequest;
use VoiceML\Model\CreateConversationsV1ServiceRoleRequest;
use VoiceML\Model\CreateConversationsV1ServiceUserRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConfigurationRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationMessageRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationParticipantRequest;
use VoiceML\Model\UpdateConversationsV1ServiceConversationRequest;
use VoiceML\Model\UpdateConversationsV1ServiceNotificationRequest;
use VoiceML\Model\UpdateConversationsV1ServiceRoleRequest;
use VoiceML\Model\UpdateConversationsV1ServiceUserRequest;
use VoiceML\Model\UpdateConversationsV1ServiceWebhookConfigurationRequest;
use VoiceML\Resource\ConversationsV1ServiceScopeResource;

/**
 * Wire-shape tests for the v0.9.0 Phase 4 surface — service-scoped
 * Conversations v1 (48 ops under `/v1/Services/{ChatServiceSid}/`).
 * Mock-Guzzle-backed; catches URL, HTTP-method, and form-encoding
 * regressions before they reach the live API.
 */
final class V090Phase4Test extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const SERVICE_SID = 'IS00000000000000000000000000000001';
    private const CONVERSATION_SID = 'CH00000000000000000000000000000001';
    private const MESSAGE_SID = 'IM00000000000000000000000000000001';
    private const RECEIPT_SID = 'DY00000000000000000000000000000001';
    private const PARTICIPANT_SID = 'MB00000000000000000000000000000001';
    private const WEBHOOK_SID = 'WH00000000000000000000000000000001';
    private const ROLE_SID = 'RL00000000000000000000000000000001';
    private const USER_SID = 'US00000000000000000000000000000001';
    private const BINDING_SID = 'BS00000000000000000000000000000001';

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

    public function testScopeReturnsScopeResource(): void
    {
        $bag = $this->makeClient([]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);
        self::assertInstanceOf(ConversationsV1ServiceScopeResource::class, $scope);
    }

    public function testServiceConversationsCreateListFetchUpdateDelete(): void
    {
        $convJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'sid' => self::CONVERSATION_SID,
            'state' => 'active',
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($convJson, 201),
            $this->jsonResponse(['conversations' => [$convJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($convJson),
            $this->jsonResponse(['account_sid' => self::ACCOUNT_SID, 'chat_service_sid' => self::SERVICE_SID, 'sid' => self::CONVERSATION_SID, 'state' => 'closed', 'attributes' => '{}', 'date_created' => '2026-06-27T12:00:00Z', 'date_updated' => '2026-06-27T12:00:00Z', 'url' => 'x']),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];
        $scope = $client->conversationsV1->services->scope(self::SERVICE_SID);

        $c = $scope->conversations->create(new CreateConversationsV1ServiceConversationRequest(
            friendlyName: 'support',
            state: 'active',
            timersInactive: 'PT1H',
        ));
        self::assertSame(self::CONVERSATION_SID, $c->sid);
        self::assertSame(self::SERVICE_SID, $c->chatServiceSid);
        $createUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Conversations', $createUri);
        self::assertStringNotContainsString(self::ACCOUNT_SID, $createUri);
        // Service-scoped Conversations v1 rides the conversations host (v0.9.2).
        self::assertSame('conversations.voicetel.com', $bag['history'][0]['request']->getUri()->getHost());
        $createBody = urldecode((string) $bag['history'][0]['request']->getBody());
        self::assertStringContainsString('FriendlyName=support', $createBody);
        self::assertStringContainsString('Timers.Inactive=PT1H', $createBody);

        self::assertCount(1, $scope->conversations->list(['PageSize' => 25])->conversations);
        self::assertStringContainsString('PageSize=25', (string) $bag['history'][1]['request']->getUri());

        self::assertSame(self::CONVERSATION_SID, $scope->conversations->fetch(self::CONVERSATION_SID)->sid);
        self::assertSame('GET', $bag['history'][2]['request']->getMethod());

        $upd = $scope->conversations->update(self::CONVERSATION_SID, new UpdateConversationsV1ServiceConversationRequest(state: 'closed'));
        self::assertSame('closed', $upd->state);

        $scope->conversations->delete(self::CONVERSATION_SID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
    }

    public function testServiceConversationMessagesAndReceipts(): void
    {
        $msgJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::MESSAGE_SID,
            'index' => 0,
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $receiptJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::RECEIPT_SID,
            'message_sid' => self::MESSAGE_SID,
            'status' => 'delivered',
            'error_code' => 0,
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($msgJson, 201),
            $this->jsonResponse($msgJson),
            $this->jsonResponse(['messages' => [$msgJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse(['delivery_receipts' => [$receiptJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($receiptJson),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $m = $scope->conversations->messages(self::CONVERSATION_SID)->create(new CreateConversationsV1ServiceConversationMessageRequest(author: 'alice', body: 'Hello'));
        self::assertSame(self::MESSAGE_SID, $m->sid);
        self::assertSame(self::SERVICE_SID, $m->chatServiceSid);
        $msgUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Conversations/' . self::CONVERSATION_SID . '/Messages', $msgUri);

        $upd = $scope->conversations->messages(self::CONVERSATION_SID)->update(self::MESSAGE_SID, new UpdateConversationsV1ServiceConversationMessageRequest(body: 'edit'));
        self::assertSame(self::MESSAGE_SID, $upd->sid);

        self::assertCount(1, $scope->conversations->messages(self::CONVERSATION_SID)->list()->messages);

        $list = $scope->conversations->messages(self::CONVERSATION_SID)->receipts(self::MESSAGE_SID)->list();
        self::assertCount(1, $list->deliveryReceipts);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Conversations/' . self::CONVERSATION_SID . '/Messages/' . self::MESSAGE_SID . '/Receipts',
            (string) $bag['history'][3]['request']->getUri(),
        );

        $r = $scope->conversations->messages(self::CONVERSATION_SID)->receipts(self::MESSAGE_SID)->fetch(self::RECEIPT_SID);
        self::assertSame('delivered', $r->status);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Conversations/' . self::CONVERSATION_SID . '/Messages/' . self::MESSAGE_SID . '/Receipts/' . self::RECEIPT_SID,
            (string) $bag['history'][4]['request']->getUri(),
        );
    }

    public function testServiceConversationParticipantsAndWebhooks(): void
    {
        $partJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::PARTICIPANT_SID,
            'attributes' => '{}',
            'identity' => 'alice',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $whJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'sid' => self::WEBHOOK_SID,
            'target' => 'webhook',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($partJson, 201),
            $this->jsonResponse($partJson),
            $this->jsonResponse($whJson, 201),
            $this->jsonResponse(['webhooks' => [$whJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            new Response(204, [], ''),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $p = $scope->conversations->participants(self::CONVERSATION_SID)
            ->create(new CreateConversationsV1ServiceConversationParticipantRequest(identity: 'alice', roleSid: self::ROLE_SID));
        self::assertSame(self::PARTICIPANT_SID, $p->sid);
        $partUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Conversations/' . self::CONVERSATION_SID . '/Participants', $partUri);

        $pUpd = $scope->conversations->participants(self::CONVERSATION_SID)
            ->update(self::PARTICIPANT_SID, new UpdateConversationsV1ServiceConversationParticipantRequest(attributes: '{"foo":"bar"}'));
        self::assertSame(self::PARTICIPANT_SID, $pUpd->sid);

        $w = $scope->conversations->webhooks(self::CONVERSATION_SID)
            ->create(new CreateConversationsV1ServiceConversationScopedWebhookRequest(
                target: 'webhook',
                configurationUrl: 'https://hooks/wh',
            ));
        self::assertSame(self::WEBHOOK_SID, $w->sid);
        $whUri = (string) $bag['history'][2]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Conversations/' . self::CONVERSATION_SID . '/Webhooks', $whUri);
        $whBody = urldecode((string) $bag['history'][2]['request']->getBody());
        self::assertStringContainsString('Configuration.Url=https://hooks/wh', $whBody);

        self::assertCount(1, $scope->conversations->webhooks(self::CONVERSATION_SID)->list()->webhooks);

        $scope->conversations->webhooks(self::CONVERSATION_SID)->delete(self::WEBHOOK_SID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
    }

    public function testServiceRolesAndUsers(): void
    {
        $roleJson = [
            'sid' => self::ROLE_SID,
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'type' => 'conversation',
            'friendly_name' => 'admin',
            'permissions' => ['sendMessage', 'editMessage'],
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $userJson = [
            'sid' => self::USER_SID,
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'identity' => 'alice',
            'attributes' => '{}',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $userConvJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'conversation_sid' => self::CONVERSATION_SID,
            'user_sid' => self::USER_SID,
            'conversation_state' => 'active',
            'notification_level' => 'default',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($roleJson, 201),
            $this->jsonResponse(['roles' => [$roleJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($roleJson),
            new Response(204, [], ''),
            $this->jsonResponse($userJson, 201),
            $this->jsonResponse($userJson),
            $this->jsonResponse(['conversations' => [$userConvJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $r = $scope->roles->create(new CreateConversationsV1ServiceRoleRequest(
            friendlyName: 'admin',
            type: 'conversation',
            permission: ['sendMessage', 'editMessage'],
        ));
        self::assertSame(self::ROLE_SID, $r->sid);
        self::assertSame(['sendMessage', 'editMessage'], $r->permissions);
        $roleUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Roles', $roleUri);
        $roleBody = urldecode((string) $bag['history'][0]['request']->getBody());
        self::assertStringContainsString('FriendlyName=admin', $roleBody);
        self::assertStringContainsString('Type=conversation', $roleBody);
        self::assertStringContainsString('sendMessage', $roleBody);

        self::assertCount(1, $scope->roles->list()->roles);

        $rUpd = $scope->roles->update(self::ROLE_SID, new UpdateConversationsV1ServiceRoleRequest(permission: ['sendMessage']));
        self::assertSame(self::ROLE_SID, $rUpd->sid);

        $scope->roles->delete(self::ROLE_SID);
        self::assertSame('DELETE', $bag['history'][3]['request']->getMethod());

        $u = $scope->users->create(new CreateConversationsV1ServiceUserRequest(identity: 'alice', friendlyName: 'Alice'));
        self::assertSame('alice', $u->identity);
        $userUri = (string) $bag['history'][4]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Users', $userUri);

        $uUpd = $scope->users->update(self::USER_SID, new UpdateConversationsV1ServiceUserRequest(friendlyName: 'Renamed'));
        self::assertSame(self::USER_SID, $uUpd->sid);

        $uList = $scope->users->conversations(self::USER_SID)->list();
        self::assertCount(1, $uList->conversations);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Users/' . self::USER_SID . '/Conversations',
            (string) $bag['history'][6]['request']->getUri(),
        );
    }

    public function testServiceConversationWithParticipantsAndParticipantConversations(): void
    {
        $cwpJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'sid' => self::CONVERSATION_SID,
            'state' => 'active',
            'attributes' => '{}',
            'friendly_name' => 'multi',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($cwpJson, 201),
            $this->jsonResponse([
                'conversations' => [[
                    'account_sid' => self::ACCOUNT_SID,
                    'chat_service_sid' => self::SERVICE_SID,
                    'conversation_state' => 'active',
                    'conversation_sid' => self::CONVERSATION_SID,
                    'conversation_date_created' => '2026-06-27T12:00:00Z',
                    'conversation_date_updated' => '2026-06-27T12:00:00Z',
                ]],
                'meta' => ['page' => 0, 'page_size' => 50],
            ]),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $c = $scope->conversationWithParticipants->create(new CreateConversationsV1ServiceConversationWithParticipantsRequest(
            friendlyName: 'multi',
            participant: ['{"identity":"alice"}', '{"identity":"bob"}'],
        ));
        self::assertSame(self::CONVERSATION_SID, $c->sid);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/ConversationWithParticipants',
            (string) $bag['history'][0]['request']->getUri(),
        );

        $pc = $scope->participantConversations->list(['Identity' => 'alice']);
        self::assertCount(1, $pc->conversations);
        $pcUri = (string) $bag['history'][1]['request']->getUri();
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/ParticipantConversations', $pcUri);
        self::assertStringContainsString('Identity=alice', $pcUri);
    }

    public function testServiceBindingsListFetchDelete(): void
    {
        $bindingJson = [
            'sid' => self::BINDING_SID,
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'binding_type' => 'apn',
            'identity' => 'alice',
            'date_created' => '2026-06-27T12:00:00Z',
            'date_updated' => '2026-06-27T12:00:00Z',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['bindings' => [$bindingJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($bindingJson),
            new Response(204, [], ''),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $list = $scope->bindings->list(['BindingType' => 'apn']);
        self::assertCount(1, $list->bindings);
        self::assertStringContainsString('BindingType=apn', (string) $bag['history'][0]['request']->getUri());

        $b = $scope->bindings->fetch(self::BINDING_SID);
        self::assertSame('apn', $b->bindingType);

        $scope->bindings->delete(self::BINDING_SID);
        self::assertSame('DELETE', $bag['history'][2]['request']->getMethod());
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Bindings/' . self::BINDING_SID,
            (string) $bag['history'][2]['request']->getUri(),
        );
    }

    public function testServiceConfigurationFetchUpdateAndNestedNotificationsWebhooks(): void
    {
        $cfgJson = [
            'chat_service_sid' => self::SERVICE_SID,
            'default_conversation_creator_role_sid' => self::ROLE_SID,
            'reachability_enabled' => true,
            'url' => 'x',
        ];
        $notifJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'log_enabled' => true,
            'url' => 'x',
        ];
        $whCfgJson = [
            'account_sid' => self::ACCOUNT_SID,
            'chat_service_sid' => self::SERVICE_SID,
            'method' => 'POST',
            'pre_webhook_url' => 'https://pre',
            'url' => 'x',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($cfgJson),
            $this->jsonResponse($cfgJson),
            $this->jsonResponse($notifJson),
            $this->jsonResponse($notifJson),
            $this->jsonResponse($whCfgJson),
            $this->jsonResponse($whCfgJson),
        ]);
        $scope = $bag['client']->conversationsV1->services->scope(self::SERVICE_SID);

        $cfg = $scope->configuration->fetch();
        self::assertSame(self::ROLE_SID, $cfg->defaultConversationCreatorRoleSid);
        self::assertStringContainsString('/v1/Services/' . self::SERVICE_SID . '/Configuration', (string) $bag['history'][0]['request']->getUri());

        $cfgUpd = $scope->configuration->update(new UpdateConversationsV1ServiceConfigurationRequest(
            defaultConversationCreatorRoleSid: self::ROLE_SID,
            reachabilityEnabled: true,
        ));
        self::assertTrue($cfgUpd->reachabilityEnabled);
        $cfgBody = urldecode((string) $bag['history'][1]['request']->getBody());
        self::assertStringContainsString('DefaultConversationCreatorRoleSid=' . self::ROLE_SID, $cfgBody);
        self::assertStringContainsString('ReachabilityEnabled=true', $cfgBody);

        $notif = $scope->configuration->notifications->fetch();
        self::assertTrue($notif->logEnabled);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Configuration/Notifications',
            (string) $bag['history'][2]['request']->getUri(),
        );

        $notifUpd = $scope->configuration->notifications->update(new UpdateConversationsV1ServiceNotificationRequest(
            logEnabled: true,
            newMessageTemplate: '${MESSAGE_BODY}',
            newMessageBadgeCountEnabled: true,
        ));
        self::assertTrue($notifUpd->logEnabled);
        $notifBody = urldecode((string) $bag['history'][3]['request']->getBody());
        self::assertStringContainsString('LogEnabled=true', $notifBody);
        self::assertStringContainsString('NewMessage.Template=${MESSAGE_BODY}', $notifBody);
        self::assertStringContainsString('NewMessage.BadgeCountEnabled=true', $notifBody);

        $wh = $scope->configuration->webhooks->fetch();
        self::assertSame('POST', $wh->method);
        self::assertStringContainsString(
            '/v1/Services/' . self::SERVICE_SID . '/Configuration/Webhooks',
            (string) $bag['history'][4]['request']->getUri(),
        );

        $whUpd = $scope->configuration->webhooks->update(new UpdateConversationsV1ServiceWebhookConfigurationRequest(
            preWebhookUrl: 'https://pre',
            method: 'POST',
            filters: ['onMessageAdded', 'onConversationAdded'],
        ));
        self::assertSame('https://pre', $whUpd->preWebhookUrl);
        $whBody = urldecode((string) $bag['history'][5]['request']->getBody());
        self::assertStringContainsString('PreWebhookUrl=https://pre', $whBody);
        self::assertStringContainsString('onMessageAdded', $whBody);
    }
}
