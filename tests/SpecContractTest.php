<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use VoiceML\Client;

/** Every resource entry point is invoked; expectations come from the public spec. */
final class SpecContractTest extends TestCase
{
    /** @var array<string,mixed> */
    private array $spec;
    /** @var array<string,bool> */
    private array $covered = [];
    /** @var array<int,object> Retain references to prevent object-id reuse. */
    private array $visited = [];
    private string $label = '';

    public function testEveryOperationMatchesThePublicContract(): void
    {
        $this->spec = json_decode((string) file_get_contents(__DIR__ . '/fixtures/voiceml-openapi.json'), true, flags: JSON_THROW_ON_ERROR);
        $handler = $this->recordingHandler();
        $http = new HttpClient(['handler' => HandlerStack::create($handler)]);
        $client = new Client(accountSid: 'AC' . str_repeat('0', 32), apiKey: 'contract-key', httpClient: $http, maxRetries: 0);
        foreach ((new \ReflectionClass($client))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $this->crawl($property->getValue($client), '$client->' . $property->getName());
        }
        $expected = [];
        foreach ($this->spec['paths'] as $item) {
            foreach ($item as $operation) {
                if (is_array($operation) && isset($operation['operationId'])) {
                    $expected[] = $operation['operationId'];
                }
            }
        }
        sort($expected);
        $actual = array_keys($this->covered);
        sort($actual);
        self::assertCount(333, $expected, 'Re-vendor and extend this test when the public operation set changes.');
        self::assertSame($expected, $actual, 'Every spec operation must be exercised (333/333).');
    }

    private function crawl(object $resource, string $label): void
    {
        $id = spl_object_id($resource);
        if (isset($this->visited[$id])) {
            return;
        }
        $this->visited[$id] = $resource;
        $class = new \ReflectionClass($resource);
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $value = $property->getValue($resource);
            if (is_object($value) && str_starts_with(get_class($value), 'VoiceML\\Resource\\')) {
                $this->crawl($value, $label . '->' . $property->getName());
            }
        }
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Follow-link helpers require a real previous response; dedicated pagination
            // tests cover them. Inherited helpers don't declare new operations.
            if ($method->isConstructor() || $method->isStatic() || $method->getName() === 'nextPage'
                || $method->getDeclaringClass()->getName() !== $class->getName()) {
                continue;
            }
            $args = [];
            foreach ($method->getParameters() as $parameter) {
                $args[$parameter->getName()] = $this->dummy($parameter->getType(), $parameter->getName(), 0);
            }
            if ($class->getShortName() === 'SipDomainsResource' && $method->getName() === 'create') {
                $args['body'] = ['DomainName' => 'sip.example.com'];
            }
            $this->label = $label . '->' . $method->getName();
            $result = $method->invokeArgs($resource, $args);
            if ($result instanceof \Generator) {
                foreach ($result as $unused) {
                    // Exhaust only the mocked first page (the response has no next link).
                }
            }
            if (is_object($result) && str_starts_with(get_class($result), 'VoiceML\\Resource\\')) {
                $this->crawl($result, $label . '->' . $method->getName() . '(...)');
            }
        }
    }

    private function dummy(?\ReflectionType $type, string $name, int $depth): mixed
    {
        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $part) {
                if ($part instanceof \ReflectionNamedType && !$part->isBuiltin()) {
                    return $this->dummy($part, $name, $depth);
                }
            }
            return $this->dummy($type->getTypes()[0], $name, $depth);
        }
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        $class = $type->getName();
        if ($class === 'string' || $class === 'mixed') {
            return ($name === 'method' || str_ends_with($name, 'Method')) && $name !== 'paymentMethod' ? 'POST' : 'sample_' . $name;
        }
        if ($class === 'int') { return 7; }
        if ($class === 'float') { return 1.5; }
        if ($class === 'bool') { return true; }
        if ($class === 'array') { return $depth === 0 ? [] : ['first', 'second']; }
        if (enum_exists($class)) { return $class::cases()[0]; }
        $reflection = new \ReflectionClass($class);
        $args = [];
        foreach ($reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
            $args[$parameter->getName()] = $this->dummy($parameter->getType(), $parameter->getName(), $depth + 1);
        }
        return $reflection->newInstanceArgs($args);
    }

    /**
     * Mock HTTP handler that contract-checks every request and answers 200 {}.
     *
     * @return callable(\Psr\Http\Message\RequestInterface): \GuzzleHttp\Promise\PromiseInterface
     */
    private function recordingHandler(): callable
    {
        return function (RequestInterface $request): \GuzzleHttp\Promise\PromiseInterface {
            $this->checkRequest($request);

            return Create::promiseFor(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        };
    }

    private function checkRequest(RequestInterface $request): void
    {
        $matches = [];
        foreach ($this->spec['paths'] as $path => $item) {
            $pattern = '~^' . preg_replace('/\\\\\{[^}]+\\\\\}/', '[^/]+', preg_quote($path, '~')) . '$~';
            if (preg_match($pattern, $request->getUri()->getPath()) === 1 && isset($item[strtolower($request->getMethod())])) {
                // Rank by literal (non-parameter) characters: a literal segment such as
                // Members/Front.json is more specific than Members/{CallSid}.json.
                $matches[strlen(preg_replace('/\{[^}]+\}/', '', $path))][] = [$item, $item[strtolower($request->getMethod())]];
            }
        }
        krsort($matches);
        $best = $matches[array_key_first($matches)] ?? [];
        self::assertCount(1, $best, $this->label . ': ' . $request->getMethod() . ' ' . $request->getUri());
        [$item, $op] = $best[0];
        $this->covered[$op['operationId']] = true;
        $params = [];
        $required = [];
        foreach (array_merge($item['parameters'] ?? [], $op['parameters'] ?? []) as $parameter) {
            $parameter = $this->resolve($parameter);
            if ($parameter['in'] === 'query') {
                $params[] = $parameter['name'];
                if ($parameter['required'] ?? false) { $required[] = $parameter['name']; }
            }
        }
        $query = $this->wireKeys($request->getUri()->getQuery());
        self::assertSame([], array_values(array_diff($query, $params)), $this->label . ': query keys');
        self::assertSame([], array_values(array_diff($required, $query)), $this->label . ': required query keys');
        $body = (string) $request->getBody();
        $contentType = str_starts_with($request->getHeaderLine('Content-Type'), 'application/json')
            ? 'application/json' : 'application/x-www-form-urlencoded';
        $bodyKeys = $contentType === 'application/json'
            ? array_keys(json_decode($body, true, flags: JSON_THROW_ON_ERROR)) : $this->wireKeys($body);
        $schema = $this->resolve($op['requestBody']['content'][$contentType]['schema'] ?? []);
        $props = array_keys($schema['properties'] ?? []);
        self::assertSame([], array_values(array_diff($bodyKeys, $props)), $this->label . ': body keys');
        self::assertSame([], array_values(array_diff($schema['required'] ?? [], $bodyKeys)), $this->label . ': required body keys');
        $tags = $op['tags'];
        $hosts = ['voiceml.voicetel.com'];
        if (array_filter($tags, static fn (string $tag): bool => str_starts_with($tag, 'ConversationsV1')) !== []) {
            $hosts = ['conversations.voicetel.com'];
        }
        if (in_array('MessagingV1Service', $tags, true)) {
            $hosts = in_array('ConversationsV1Service', $tags, true)
                ? ['messaging.voicetel.com', 'conversations.voicetel.com'] : ['messaging.voicetel.com'];
        }
        self::assertContains($request->getUri()->getHost(), $hosts, $this->label . ': product host');
        $authenticated = ($op['security'] ?? $this->spec['security']) !== [];
        self::assertSame($authenticated, $request->hasHeader('Authorization'), $this->label . ': auth');
        if ($authenticated) {
            self::assertSame('Basic ' . base64_encode('AC' . str_repeat('0', 32) . ':contract-key'), $request->getHeaderLine('Authorization'));
        }
    }

    /** @param array<string,mixed> $schema @return array<string,mixed> */
    private function resolve(array $schema): array
    {
        if (!isset($schema['$ref'])) { return $schema; }
        $value = $this->spec;
        foreach (explode('/', substr($schema['$ref'], 2)) as $key) { $value = $value[$key]; }
        return $value;
    }

    /** @return list<string> */
    private function wireKeys(string $wire): array
    {
        if ($wire === '') { return []; }
        return array_values(array_unique(array_map(static function (string $pair): string {
            return rawurldecode(explode('=', $pair, 2)[0]);
        }, explode('&', $wire))));
    }
}
