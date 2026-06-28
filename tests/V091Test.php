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
use VoiceML\Model\AssistantsV1Assistant;
use VoiceML\Model\AssistantsV1AssistantWithToolsAndKnowledge;
use VoiceML\Model\AssistantsV1Knowledge;
use VoiceML\Model\AssistantsV1Tool;
use VoiceML\Model\AssistantsV1ToolWithPolicies;
use VoiceML\Model\CreateAssistantsV1AssistantRequest;
use VoiceML\Model\CreateAssistantsV1FeedbackRequest;
use VoiceML\Model\CreateAssistantsV1KnowledgeRequest;
use VoiceML\Model\CreateAssistantsV1ToolRequest;
use VoiceML\Model\SendAssistantsV1AssistantMessageRequest;
use VoiceML\Model\UpdateAssistantsV1AssistantRequest;
use VoiceML\Model\UpdateAssistantsV1KnowledgeRequest;
use VoiceML\Model\UpdateAssistantsV1ToolRequest;
use VoiceML\Resource\AssistantsV1AssistantScopeResource;
use VoiceML\Resource\AssistantsV1KnowledgeScopeResource;
use VoiceML\Resource\AssistantsV1SessionScopeResource;

/**
 * Wire-shape tests for the v0.9.1 Assistants v1 surface (#421 Phase 5) —
 * 7 families, 30 ops under `/v1/Assistants`, `/v1/Tools`, `/v1/Knowledge`,
 * `/v1/Sessions`, `/v1/Policies`. Mock-Guzzle-backed; catches URL,
 * HTTP-method, and JSON-body regressions before they reach the live API.
 *
 * Note: AssistantsV1 uses `application/json` request bodies (not the form
 * encoding the rest of the SDK uses). All POST/PUT bodies in this surface
 * should serialize with snake_case keys.
 */
final class V091Test extends TestCase
{
    private const ACCOUNT_SID = 'AC00000000000000000000000000000001';
    private const API_KEY = 'test-api-key';
    private const ASSISTANT_ID = 'aia_asst_01abc';
    private const TOOL_ID = 'aia_tool_01def';
    private const KNOWLEDGE_ID = 'aia_know_01ghi';
    private const SESSION_ID = 'sess_01xyz';
    private const MESSAGE_ID = 'aia_msg_01uvw';
    private const FEEDBACK_ID = 'aia_fdbk_01rst';
    private const POLICY_ID = 'aia_plcy_01mno';

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

    public function testScopeFactoriesReturnScopeResources(): void
    {
        $bag = $this->makeClient([]);
        $client = $bag['client'];
        self::assertInstanceOf(AssistantsV1AssistantScopeResource::class, $client->assistantsV1->assistants(self::ASSISTANT_ID));
        self::assertInstanceOf(AssistantsV1KnowledgeScopeResource::class, $client->assistantsV1->knowledge(self::KNOWLEDGE_ID));
        self::assertInstanceOf(AssistantsV1SessionScopeResource::class, $client->assistantsV1->sessions(self::SESSION_ID));
    }

    public function testAssistantsCrudWireShape(): void
    {
        $asstJson = [
            'account_sid' => self::ACCOUNT_SID,
            'id' => self::ASSISTANT_ID,
            'name' => 'Helpdesk',
            'owner' => 'support',
            'model' => 'gpt-4o-mini',
            'personality_prompt' => 'You are a helpful agent.',
            'customer_ai' => ['perception_engine_enabled' => true],
            'url' => 'https://x/v1/Assistants/' . self::ASSISTANT_ID,
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $asstWithJson = $asstJson + ['tools' => [], 'knowledge' => []];
        $bag = $this->makeClient([
            $this->jsonResponse($asstJson, 201),
            $this->jsonResponse(['assistants' => [$asstJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($asstWithJson),
            $this->jsonResponse(['name' => 'Helpdesk2'] + $asstJson),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $a = $client->assistantsV1->assistants->create(new CreateAssistantsV1AssistantRequest(
            name: 'Helpdesk',
            owner: 'support',
            personalityPrompt: 'You are a helpful agent.',
            model: 'gpt-4o-mini',
            customerAi: ['perception_engine_enabled' => true],
        ));
        self::assertInstanceOf(AssistantsV1Assistant::class, $a);
        self::assertSame(self::ASSISTANT_ID, $a->id);
        self::assertSame('Helpdesk', $a->name);
        self::assertSame('POST', $bag['history'][0]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants', (string) $bag['history'][0]['request']->getUri());
        $createBody = (string) $bag['history'][0]['request']->getBody();
        $createDecoded = json_decode($createBody, true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('Helpdesk', $createDecoded['name']);
        self::assertSame('support', $createDecoded['owner']);
        self::assertSame('gpt-4o-mini', $createDecoded['model']);
        self::assertSame('You are a helpful agent.', $createDecoded['personality_prompt']);
        self::assertSame(['perception_engine_enabled' => true], $createDecoded['customer_ai']);
        self::assertSame('application/json', $bag['history'][0]['request']->getHeaderLine('Content-Type'));

        $list = $client->assistantsV1->assistants->list(['PageSize' => 25]);
        self::assertCount(1, $list->assistants);
        self::assertStringContainsString('PageSize=25', (string) $bag['history'][1]['request']->getUri());

        $aw = $client->assistantsV1->assistants->fetch(self::ASSISTANT_ID);
        self::assertInstanceOf(AssistantsV1AssistantWithToolsAndKnowledge::class, $aw);
        self::assertSame(self::ASSISTANT_ID, $aw->id);
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID, (string) $bag['history'][2]['request']->getUri());

        $upd = $client->assistantsV1->assistants->update(self::ASSISTANT_ID, new UpdateAssistantsV1AssistantRequest(name: 'Helpdesk2'));
        self::assertSame('Helpdesk2', $upd->name);
        self::assertSame('PUT', $bag['history'][3]['request']->getMethod());
        $updDecoded = json_decode((string) $bag['history'][3]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(['name' => 'Helpdesk2'], $updDecoded);

        $client->assistantsV1->assistants->delete(self::ASSISTANT_ID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID, (string) $bag['history'][4]['request']->getUri());
    }

    public function testAssistantToolsAttachDetachAndListScoped(): void
    {
        $toolJson = [
            'account_sid' => self::ACCOUNT_SID,
            'id' => self::TOOL_ID,
            'name' => 'web_search',
            'type' => 'webhook',
            'enabled' => true,
            'requires_auth' => false,
            'meta' => ['kind' => 'http'],
            'url' => 'https://x/v1/Tools/' . self::TOOL_ID,
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['tools' => [$toolJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            new Response(204, [], ''),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $scoped = $client->assistantsV1->assistants(self::ASSISTANT_ID);
        $list = $scoped->tools->list(['PageSize' => 10]);
        self::assertCount(1, $list->tools);
        $listUri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Assistants/' . self::ASSISTANT_ID . '/Tools', $listUri);
        self::assertStringContainsString('PageSize=10', $listUri);

        $scoped->tools->attach(self::TOOL_ID);
        self::assertSame('POST', $bag['history'][1]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID . '/Tools/' . self::TOOL_ID, (string) $bag['history'][1]['request']->getUri());

        $scoped->tools->detach(self::TOOL_ID);
        self::assertSame('DELETE', $bag['history'][2]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID . '/Tools/' . self::TOOL_ID, (string) $bag['history'][2]['request']->getUri());
    }

    public function testAssistantKnowledgeAttachDetachAndListScoped(): void
    {
        $knowJson = [
            'account_sid' => self::ACCOUNT_SID,
            'id' => self::KNOWLEDGE_ID,
            'name' => 'manual',
            'type' => 'web',
            'description' => 'product manual',
            'status' => 'COMPLETED',
            'embedding_model' => 'text-embedding-3-small',
            'knowledge_source_details' => ['source' => 'https://docs/example'],
            'url' => 'https://x/v1/Knowledge/' . self::KNOWLEDGE_ID,
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['knowledge' => [$knowJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            new Response(204, [], ''),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $scoped = $client->assistantsV1->assistants(self::ASSISTANT_ID);
        $list = $scoped->knowledge->list();
        self::assertCount(1, $list->knowledge);
        self::assertSame('COMPLETED', $list->knowledge[0]->status);
        self::assertStringContainsString('/v1/Assistants/' . self::ASSISTANT_ID . '/Knowledge', (string) $bag['history'][0]['request']->getUri());

        $scoped->knowledge->attach(self::KNOWLEDGE_ID);
        self::assertSame('POST', $bag['history'][1]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID . '/Knowledge/' . self::KNOWLEDGE_ID, (string) $bag['history'][1]['request']->getUri());

        $scoped->knowledge->detach(self::KNOWLEDGE_ID);
        self::assertSame('DELETE', $bag['history'][2]['request']->getMethod());
    }

    public function testAssistantFeedbacksListAndCreate(): void
    {
        $fdbkJson = [
            'id' => self::FEEDBACK_ID,
            'account_sid' => self::ACCOUNT_SID,
            'assistant_id' => self::ASSISTANT_ID,
            'session_id' => self::SESSION_ID,
            'message_id' => self::MESSAGE_ID,
            'score' => 0.92,
            'text' => 'good',
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['feedbacks' => [$fdbkJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($fdbkJson, 201),
        ]);
        $client = $bag['client'];
        $scoped = $client->assistantsV1->assistants(self::ASSISTANT_ID);

        $list = $scoped->feedbacks->list();
        self::assertCount(1, $list->feedbacks);
        self::assertSame(0.92, $list->feedbacks[0]->score);
        self::assertStringContainsString('/v1/Assistants/' . self::ASSISTANT_ID . '/Feedbacks', (string) $bag['history'][0]['request']->getUri());

        $created = $scoped->feedbacks->create(new CreateAssistantsV1FeedbackRequest(
            sessionId: self::SESSION_ID,
            messageId: self::MESSAGE_ID,
            score: 0.92,
            text: 'good',
        ));
        self::assertSame(self::FEEDBACK_ID, $created->id);
        self::assertSame('POST', $bag['history'][1]['request']->getMethod());
        $body = json_decode((string) $bag['history'][1]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(self::SESSION_ID, $body['session_id']);
        self::assertSame(self::MESSAGE_ID, $body['message_id']);
        self::assertSame(0.92, $body['score']);
        self::assertSame('good', $body['text']);
    }

    public function testAssistantSendMessage(): void
    {
        $resp = [
            'status' => 'ok',
            'session_id' => self::SESSION_ID,
            'account_sid' => self::ACCOUNT_SID,
            'flagged' => false,
            'aborted' => false,
            'body' => 'Hi there!',
        ];
        $bag = $this->makeClient([$this->jsonResponse($resp)]);
        $client = $bag['client'];
        $r = $client->assistantsV1->assistants(self::ASSISTANT_ID)->messages->create(new SendAssistantsV1AssistantMessageRequest(
            identity: 'user:alice',
            body: 'Hello?',
            sessionId: self::SESSION_ID,
            mode: 'sync',
        ));
        self::assertSame('ok', $r->status);
        self::assertSame(self::SESSION_ID, $r->sessionId);
        self::assertSame('Hi there!', $r->body);
        self::assertSame('POST', $bag['history'][0]['request']->getMethod());
        self::assertStringEndsWith('/v1/Assistants/' . self::ASSISTANT_ID . '/Messages', (string) $bag['history'][0]['request']->getUri());
        $body = json_decode((string) $bag['history'][0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('user:alice', $body['identity']);
        self::assertSame('Hello?', $body['body']);
        self::assertSame(self::SESSION_ID, $body['session_id']);
        self::assertSame('sync', $body['mode']);
    }

    public function testToolsTopLevelCrud(): void
    {
        $toolJson = [
            'account_sid' => self::ACCOUNT_SID,
            'id' => self::TOOL_ID,
            'name' => 'web_search',
            'type' => 'webhook',
            'enabled' => true,
            'requires_auth' => false,
            'meta' => ['kind' => 'http'],
            'description' => 'searches the web',
            'url' => 'https://x/v1/Tools/' . self::TOOL_ID,
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $toolWithJson = $toolJson + ['policies' => [[
            'id' => self::POLICY_ID,
            'type' => 'tool',
            'policy_details' => ['require_user_consent' => true],
        ]]];
        $bag = $this->makeClient([
            $this->jsonResponse($toolJson, 201),
            $this->jsonResponse(['tools' => [$toolJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($toolWithJson),
            $this->jsonResponse(['enabled' => false] + $toolJson),
            new Response(204, [], ''),
        ]);
        $client = $bag['client'];

        $t = $client->assistantsV1->tools->create(new CreateAssistantsV1ToolRequest(
            name: 'web_search',
            type: 'webhook',
            enabled: true,
            assistantId: self::ASSISTANT_ID,
            description: 'searches the web',
            meta: ['kind' => 'http'],
        ));
        self::assertInstanceOf(AssistantsV1Tool::class, $t);
        self::assertSame(self::TOOL_ID, $t->id);
        self::assertTrue($t->enabled);
        $createBody = json_decode((string) $bag['history'][0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('web_search', $createBody['name']);
        self::assertSame('webhook', $createBody['type']);
        self::assertTrue($createBody['enabled']);
        self::assertSame(self::ASSISTANT_ID, $createBody['assistant_id']);
        self::assertSame('searches the web', $createBody['description']);
        self::assertSame(['kind' => 'http'], $createBody['meta']);
        self::assertStringEndsWith('/v1/Tools', (string) $bag['history'][0]['request']->getUri());

        $list = $client->assistantsV1->tools->list(['AssistantId' => self::ASSISTANT_ID, 'PageSize' => 5]);
        self::assertCount(1, $list->tools);
        $listUri = (string) $bag['history'][1]['request']->getUri();
        self::assertStringContainsString('AssistantId=' . self::ASSISTANT_ID, $listUri);
        self::assertStringContainsString('PageSize=5', $listUri);

        $tw = $client->assistantsV1->tools->fetch(self::TOOL_ID);
        self::assertInstanceOf(AssistantsV1ToolWithPolicies::class, $tw);
        self::assertCount(1, $tw->policies);
        self::assertSame(self::POLICY_ID, $tw->policies[0]->id);

        $u = $client->assistantsV1->tools->update(self::TOOL_ID, new UpdateAssistantsV1ToolRequest(enabled: false));
        self::assertFalse($u->enabled);
        self::assertSame('PUT', $bag['history'][3]['request']->getMethod());
        $updBody = json_decode((string) $bag['history'][3]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(['enabled' => false], $updBody);

        $client->assistantsV1->tools->delete(self::TOOL_ID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());
    }

    public function testKnowledgeTopLevelCrudAndScopedStatusChunks(): void
    {
        $knowJson = [
            'account_sid' => self::ACCOUNT_SID,
            'id' => self::KNOWLEDGE_ID,
            'name' => 'manual',
            'type' => 'web',
            'description' => 'product manual',
            'status' => 'COMPLETED',
            'embedding_model' => 'text-embedding-3-small',
            'knowledge_source_details' => ['source' => 'https://docs/example'],
            'url' => 'https://x/v1/Knowledge/' . self::KNOWLEDGE_ID,
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse($knowJson, 201),
            $this->jsonResponse(['knowledge' => [$knowJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($knowJson),
            $this->jsonResponse($knowJson),
            new Response(204, [], ''),
            $this->jsonResponse([
                'account_sid' => self::ACCOUNT_SID,
                'status' => 'COMPLETED',
                'last_status' => 'INDEXING',
                'date_updated' => '2026-06-28T12:00:00Z',
            ]),
            $this->jsonResponse([
                'chunks' => [[
                    'account_sid' => self::ACCOUNT_SID,
                    'content' => 'hello world',
                    'metadata' => ['idx' => 0],
                    'date_created' => '2026-06-28T12:00:00Z',
                    'date_updated' => '2026-06-28T12:00:00Z',
                ]],
                'meta' => ['page' => 0, 'page_size' => 50],
            ]),
        ]);
        $client = $bag['client'];

        $k = $client->assistantsV1->knowledge->create(new CreateAssistantsV1KnowledgeRequest(
            name: 'manual',
            type: 'web',
            assistantId: self::ASSISTANT_ID,
            description: 'product manual',
            embeddingModel: 'text-embedding-3-small',
            knowledgeSourceDetails: ['source' => 'https://docs/example'],
        ));
        self::assertInstanceOf(AssistantsV1Knowledge::class, $k);
        self::assertSame(self::KNOWLEDGE_ID, $k->id);
        $createBody = json_decode((string) $bag['history'][0]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('manual', $createBody['name']);
        self::assertSame('web', $createBody['type']);
        self::assertSame(self::ASSISTANT_ID, $createBody['assistant_id']);
        self::assertSame('text-embedding-3-small', $createBody['embedding_model']);
        self::assertSame(['source' => 'https://docs/example'], $createBody['knowledge_source_details']);
        self::assertStringEndsWith('/v1/Knowledge', (string) $bag['history'][0]['request']->getUri());

        $list = $client->assistantsV1->knowledge->list(['AssistantId' => self::ASSISTANT_ID]);
        self::assertCount(1, $list->knowledge);
        self::assertStringContainsString('AssistantId=' . self::ASSISTANT_ID, (string) $bag['history'][1]['request']->getUri());

        $f = $client->assistantsV1->knowledge->fetch(self::KNOWLEDGE_ID);
        self::assertSame(self::KNOWLEDGE_ID, $f->id);

        $u = $client->assistantsV1->knowledge->update(self::KNOWLEDGE_ID, new UpdateAssistantsV1KnowledgeRequest(description: 'updated'));
        self::assertSame(self::KNOWLEDGE_ID, $u->id);
        self::assertSame('PUT', $bag['history'][3]['request']->getMethod());
        $updBody = json_decode((string) $bag['history'][3]['request']->getBody(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(['description' => 'updated'], $updBody);

        $client->assistantsV1->knowledge->delete(self::KNOWLEDGE_ID);
        self::assertSame('DELETE', $bag['history'][4]['request']->getMethod());

        $st = $client->assistantsV1->knowledge(self::KNOWLEDGE_ID)->status->fetch();
        self::assertSame('COMPLETED', $st->status);
        self::assertSame('INDEXING', $st->lastStatus);
        self::assertStringEndsWith('/v1/Knowledge/' . self::KNOWLEDGE_ID . '/Status', (string) $bag['history'][5]['request']->getUri());

        $ch = $client->assistantsV1->knowledge(self::KNOWLEDGE_ID)->chunks->list(['PageSize' => 1]);
        self::assertCount(1, $ch->chunks);
        self::assertSame('hello world', $ch->chunks[0]->content);
        $chunksUri = (string) $bag['history'][6]['request']->getUri();
        self::assertStringContainsString('/v1/Knowledge/' . self::KNOWLEDGE_ID . '/Chunks', $chunksUri);
        self::assertStringContainsString('PageSize=1', $chunksUri);
    }

    public function testSessionsListFetchAndSessionMessages(): void
    {
        $sessJson = [
            'id' => self::SESSION_ID,
            'account_sid' => self::ACCOUNT_SID,
            'assistant_id' => self::ASSISTANT_ID,
            'verified' => true,
            'identity' => 'user:alice',
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $msgJson = [
            'id' => self::MESSAGE_ID,
            'account_sid' => self::ACCOUNT_SID,
            'assistant_id' => self::ASSISTANT_ID,
            'session_id' => self::SESSION_ID,
            'identity' => 'user:alice',
            'role' => 'assistant',
            'content' => ['text' => 'Hi'],
            'meta' => ['tokens' => 10],
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['sessions' => [$sessJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
            $this->jsonResponse($sessJson),
            $this->jsonResponse(['messages' => [$msgJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
        ]);
        $client = $bag['client'];

        $list = $client->assistantsV1->sessions->list(['PageSize' => 3]);
        self::assertCount(1, $list->sessions);
        self::assertStringContainsString('PageSize=3', (string) $bag['history'][0]['request']->getUri());
        self::assertStringContainsString('/v1/Sessions', (string) $bag['history'][0]['request']->getUri());

        $s = $client->assistantsV1->sessions->fetch(self::SESSION_ID);
        self::assertSame(self::SESSION_ID, $s->id);
        self::assertStringEndsWith('/v1/Sessions/' . self::SESSION_ID, (string) $bag['history'][1]['request']->getUri());

        $ml = $client->assistantsV1->sessions(self::SESSION_ID)->messages->list();
        self::assertCount(1, $ml->messages);
        self::assertSame('assistant', $ml->messages[0]->role);
        self::assertSame(['text' => 'Hi'], $ml->messages[0]->content);
        self::assertStringEndsWith('/v1/Sessions/' . self::SESSION_ID . '/Messages', (string) $bag['history'][2]['request']->getUri());
    }

    public function testPoliciesList(): void
    {
        $polJson = [
            'id' => self::POLICY_ID,
            'account_sid' => self::ACCOUNT_SID,
            'name' => 'requires-consent',
            'description' => 'demand user consent',
            'type' => 'tool',
            'policy_details' => ['require_user_consent' => true],
            'date_created' => '2026-06-28T12:00:00Z',
            'date_updated' => '2026-06-28T12:00:00Z',
        ];
        $bag = $this->makeClient([
            $this->jsonResponse(['policies' => [$polJson], 'meta' => ['page' => 0, 'page_size' => 50]]),
        ]);
        $client = $bag['client'];
        $list = $client->assistantsV1->policies->list(['ToolId' => self::TOOL_ID, 'PageSize' => 100]);
        self::assertCount(1, $list->policies);
        self::assertSame(self::POLICY_ID, $list->policies[0]->id);
        $uri = (string) $bag['history'][0]['request']->getUri();
        self::assertStringContainsString('/v1/Policies', $uri);
        self::assertStringContainsString('ToolId=' . self::TOOL_ID, $uri);
        self::assertStringContainsString('PageSize=100', $uri);
    }
}
