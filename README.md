# 📞 VoiceML PHP SDK

The official PHP client for the [VoiceML REST API](https://voicetel.com/docs/api/v0.7/voiceml/) — Twilio-compatible outbound voice and answering-machine-detection from VoiceTel, with modern PHP 8.1+ ergonomics and battle-tested Guzzle transport.

![Version](https://img.shields.io/badge/version-0.8.1-blue)
![PHP](https://img.shields.io/badge/php-%E2%89%A58.1-blue)
![License](https://img.shields.io/badge/license-MIT%20%2B%20Commons%20Clause-green)
![Tests](https://img.shields.io/badge/tests-55%20unit-brightgreen)
![Typed](https://img.shields.io/badge/typed-readonly%20properties-blue)

## 📚 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Quickstart](#-quickstart)
- [Authentication](#-authentication)
- [Resource Reference](#%EF%B8%8F-resource-reference)
- [Error Handling](#-error-handling)
- [Pagination](#-pagination)
- [Migration from twilio-php](#-migration-from-twilio-php)
- [Rate Limits](#%EF%B8%8F-rate-limits)
- [Development](#%EF%B8%8F-development)
- [API Documentation](#-api-documentation)
- [Contributors](#-contributors)
- [Sponsors](#-sponsors)
- [License](#-license)

## ✨ Features

### 🛡️ Modern PHP, Strictly Typed
- **PHP 8.1+** end-to-end — `readonly` properties, named arguments, constructor property promotion, `declare(strict_types=1)` everywhere.
- **Typed request and response models** for every one of the 81 API operations across 9 resource families — `Call->sid`, `Recording->duration`, `Queue->currentSize` all hint cleanly in your IDE.
- **Twilio-compatible wire shapes** — `AccountSid`, `From`, `To`, status callbacks, pagination envelopes — match what Twilio's Programmable Voice API documents.
- **PSR-4 autoloading** under `VoiceML\` — plays nicely with Symfony, Laravel, and every other Composer-aware framework.

### 🔁 Production-Grade Transport
- Built on **Guzzle 7** (`guzzlehttp/guzzle: ^7.8`) — the de-facto HTTP client in the PHP ecosystem.
- **Automatic retry** with exponential backoff on 429 / 5xx — honors `Retry-After` headers.
- **Configurable timeout** per client (defaults to 30s) and configurable `maxRetries` (defaults to 2).
- **HTTP Basic auth** with `AccountSid:ApiKey` — exactly what the Twilio SDK uses, so existing credentials work unchanged.
- **Structured exception hierarchy** — `RateLimitException`, `AuthenticationException`, `NotFoundException`, etc. all subclasses of `ApiException` you can catch broadly or narrowly.

### 📞 Complete API Coverage
- **Calls** — originate, fetch, terminate, update + per-call recordings, streams, siprec, transcriptions, notifications, events, and the `/Calls/{sid}/Payments` lifecycle (Pay TwiML companion).
- **Conferences** — list, fetch, end conferences, plus participants (mute / hold / kick) and conference-scoped recordings.
- **Queues** — create, list, update, delete, peek, dequeue (front or specific member).
- **Applications** — CRUD on stored TwiML + callback bundles.
- **Recordings** — account-wide list, metadata fetch, audio fetch (follows S3 redirect), delete.
- **Messages** — create, fetch, list (To/From/DateSent filters + pagination), update (Body redaction; Status=canceled), delete.
- **IncomingPhoneNumbers** — list, fetch, update.
- **Notifications** — fetch, list.
- **SIP** — SIP Trunking: Domains (CRUD), CredentialLists + Credentials (CRUD), IpAccessControlLists + IpAddresses (CRUD), Domain↔ACL/CredentialList mappings (historical, Auth/Calls, Auth/Registrations namespaces).
- **Routes V2** — Twilio Inbound Processing Region API: `$client->routesV2->sipDomains->fetch($name)` / `update($name, voiceRegion: 'us1', friendlyName: 'ingress')`.
- **Diagnostics** — `/health` deep probe, OpenAPI spec.

### 🧪 Tested
- **65 unit tests** with mocked Guzzle handlers — every method and every error path exercised, no network in CI.
- **Conformance test suite** that validates wire shapes against the published OpenAPI document — spec drift gets caught at parse time.

### 📦 Clean Distribution
- Zero codegen footprint — every byte hand-written.
- Single Composer package, single namespace, no surprise transitive deps beyond Guzzle.

## 🚀 Installation

```bash
composer require voicetel/voiceml
```

Requires PHP 8.1 or later and the `json` extension (bundled with PHP).

## 🏁 Quickstart

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use VoiceML\Client;
use VoiceML\Model\CreateCallRequest;

$client = new Client(
    accountSid: getenv('VOICEML_ACCOUNT_SID'),
    apiKey: getenv('VOICEML_API_KEY'),
);

$call = $client->calls->create(new CreateCallRequest(
    to: '+18005551234',
    from: '+18005550000',
    url: 'https://example.com/twiml',
    machineDetection: 'DetectMessageEnd',
));

echo $call->sid, ' ', $call->statusRaw, PHP_EOL;

foreach ($client->queues->list()->queues as $q) {
    echo $q->friendlyName, ' ', $q->currentSize, PHP_EOL;
}
```

## 🔑 Authentication

Every endpoint uses **HTTP Basic** with your `AccountSid` as the username and your per-tenant API key as the password — identical to Twilio's auth shape, so credentials issued for Twilio code work here unchanged.

```php
<?php

use VoiceML\Client;

$client = new Client(
    accountSid: 'AC…',
    apiKey: '…',
);

$health = $client->diagnostics->health();  // uses your AccountSid + key on every call
```

For ports from the Twilio PHP SDK, the credential may be passed as `authToken:` instead of `apiKey:` — they are aliases:

```php
$client = new VoiceML\Client(accountSid: $sid, authToken: $token);
```

Passing both raises `ConfigurationException`.

> Don't have credentials yet? See **[voicetel.com/docs/api/v0.7/voiceml/](https://voicetel.com/docs/api/v0.7/voiceml/)** for issuance and rotation.

## 🗺️ Resource Reference

| Resource | Methods | Covers |
|---|---|---|
| `$client->calls` | originate, fetch, list, terminate, update | + per-call recordings, streams, siprec, transcriptions, notifications, events, payments |
| `$client->conferences` | list, fetch, end | participants (mute / hold / kick), conference-scoped recordings |
| `$client->queues` | create, list, update, delete | peek, dequeue (front or specific member) |
| `$client->applications` | CRUD on TwiML + callback bundles | |
| `$client->recordings` | account-wide list, metadata, audio fetch, delete | follows S3 redirect for audio |
| `$client->messages` | create, fetch, list, update, delete | To/From/DateSent filters; Body redaction; Status=canceled |
| `$client->incomingPhoneNumbers` | list, fetch, update | |
| `$client->notifications` | fetch, list | |
| `$client->diagnostics` | `/health`, OpenAPI spec | |

Every method that takes a request body accepts a typed model imported from `VoiceML\Model`:

```php
<?php

use VoiceML\Client;
use VoiceML\Model\CreateCallRequest;
use VoiceML\Model\StartPaymentRequest;

$client = new Client(accountSid: 'AC…', apiKey: '…');

$call = $client->calls->create(new CreateCallRequest(
    to: '+18005551234',
    from: '+18005550000',
    url: 'https://example.com/twiml',
));

// On a live call, open a Pay session:
$session = $client->calls->startPayment($call->sid, new StartPaymentRequest(
    idempotencyKey: 'order-482917',
    statusCallback: 'https://example.com/pay-status',
));

echo $session->sid, ' ', $session->status, PHP_EOL;
```

### Recording audio

The audio fetch follows VoiceML's single 302→S3 redirect transparently:

```php
$audio = $client->recordings->getAudio($recordingSid);
file_put_contents("{$recordingSid}.wav", $audio->content);
```

If the recording is gone (no local file and no S3 key), `GoneException` is raised.

## 🚨 Error Handling

All HTTP errors extend `VoiceML\Exception\ApiException`, which extends `VoiceML\Exception\VoiceMLException` (a `RuntimeException`). Catch broadly or narrowly:

| Status | Exception |
|--------|-----------|
| 400 | `VoiceML\Exception\BadRequestException` |
| 401 | `VoiceML\Exception\AuthenticationException` |
| 403 | `VoiceML\Exception\PermissionDeniedException` |
| 404 | `VoiceML\Exception\NotFoundException` |
| 409 | `VoiceML\Exception\ConflictException` |
| 410 | `VoiceML\Exception\GoneException` |
| 429 | `VoiceML\Exception\RateLimitException` |
| 501 | `VoiceML\Exception\NotImplementedApiException` |
| 5xx | `VoiceML\Exception\ServerException` |
| other | `VoiceML\Exception\ApiException` |

```php
<?php

use VoiceML\Client;
use VoiceML\Exception\NotFoundException;
use VoiceML\Exception\RateLimitException;

$client = new Client(accountSid: 'AC…', apiKey: '…');

try {
    $call = $client->calls->get('CA0000000000000000000000000000aaaa');
} catch (NotFoundException $e) {
    echo "That call isn't on your account.", PHP_EOL;
} catch (RateLimitException $e) {
    $retry = $e->body['retry_after'] ?? '?';
    echo "Slow down — retry in {$retry}s", PHP_EOL;
}
```

The Twilio-compatible error body (`code`, `message`, `more_info`, `status`) is parsed into `$e->errorCode`, `$e->getMessage()`, `$e->moreInfo` (also `$e->getMoreInfo()`), and `$e->body`.

## 📄 Pagination

List operations return a `…List` model with a Twilio-compatible pagination envelope (`page`, `pageSize`, `total`, `nextPageUri`, `previousPageUri`, …). For `/Calls` and `/Messages`, use the `iterate()` generator helper to walk all pages transparently:

```php
foreach ($client->calls->iterate(status: 'completed', pageSize: 200) as $call) {
    process($call);
}

foreach ($client->messages->iterate(from: '+18005550000', pageSize: 200) as $msg) {
    archive($msg);
}
```

For other resources, page manually with `$client-><resource>->list(new ListXParams(page: $n))` and walk `nextPageUri` until null.

## 🔁 Migration from twilio-php

The `accountSid` + API token pair Twilio's SDK validates in its constructor works unchanged here:

```php
<?php

// Before — Twilio
// use Twilio\Rest\Client as TwilioClient;
// $client = new TwilioClient('AC…', '<token>');
// $call = $client->calls->create('+18005551234', '+18005550000', ['url' => '...']);

// After — VoiceML (Twilio-compatible)
use VoiceML\Client;
use VoiceML\Model\CreateCallRequest;

$client = new Client(accountSid: 'AC…', apiKey: '<api-key>');
$call = $client->calls->create(new CreateCallRequest(
    to: '+18005551234',
    from: '+18005550000',
    url: 'https://example.com/twiml',
));
```

Method names follow the resource map above (`$client->calls->create(...)`, `$client->queues->list()`, …) rather than Twilio's `$client->api->v2010->accounts($sid)->calls->create(...)` chain — flatter, fewer keystrokes, same wire format on the way out. Request bodies are typed `VoiceML\Model\…` objects instead of associative arrays, so your IDE catches typos before runtime.

## ⏱️ Rate Limits

VoiceML applies per-tenant rate limits at the edge. The SDK automatically retries 429 responses with `Retry-After` honored, up to `maxRetries` (default `2`). To bump it:

```php
$client = new VoiceML\Client(
    accountSid: 'AC…',
    apiKey: '…',
    maxRetries: 4,
    timeout: 60.0,
);
```

## 🛠️ Development

```bash
git clone https://github.com/voicetel/voiceml-php-sdk
cd voiceml-php-sdk
composer install

# Unit tests (fast, no network)
vendor/bin/phpunit

# Conformance test (OpenAPI shape coverage)
vendor/bin/phpunit tests/ConformanceTest.php
```

## 📖 API Documentation

- **Reference docs:** [voicetel.com/docs/api/v0.7/voiceml/](https://voicetel.com/docs/api/v0.7/voiceml/)
- **Validator:** [voicetel.com/voiceml/validator/](https://voicetel.com/voiceml/validator/)
- **SDK catalogue:** [voicetel.com/docs/voiceml-sdks/](https://voicetel.com/docs/voiceml-sdks/)
- **Type definitions:** see the `VoiceML\Model` namespace — every wire shape has a typed PHP class.

## 🙌 Contributors

- [Michael Mavroudis](https://github.com/mavroudis) — Lead Developer

Contributions welcome. Open an issue describing the change you want to make, or send a pull request against `main`.

## 💖 Sponsors

| Sponsor | Contribution |
|---------|--------------|
| [VoiceTel Communications](https://voicetel.com) | Primary development and production hosting |

## 📄 License

MIT with the Commons Clause restriction. See [LICENSE](LICENSE) and [voicetel.com/legal/](https://voicetel.com/legal/).
