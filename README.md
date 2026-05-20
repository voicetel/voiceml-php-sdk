# VoiceML PHP SDK

Official PHP client for the [VoiceML](https://voicetel.com/docs/api/v0.6/voiceml/) REST API — VoiceTel's outbound voice and AMD service with a Twilio-compatible REST surface.

- **PHP:** 8.1+
- **HTTP:** [Guzzle 7](https://github.com/guzzle/guzzle) (`guzzlehttp/guzzle: ^7.8`)
- **Auth:** HTTP Basic — `AccountSid` (Twilio-format `AC` + 32 hex) as username, per-tenant API key as password
- **Server:** `https://voiceml.voicetel.com`

The wire shape, auth model, error codes, and pagination envelope all match Twilio's documented behaviour — so existing Twilio client patterns map across.

## Install

```bash
composer require voicetel/voiceml
```

## Quickstart

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
```

## Resources

| Property                      | Surface                                                                                          |
| ----------------------------- | ------------------------------------------------------------------------------------------------ |
| `$client->calls`              | `/Calls` + Recordings / Streams / Siprec / Transcriptions / Notifications / Events sub-resources |
| `$client->conferences`        | `/Conferences`, participants, conference recordings                                              |
| `$client->queues`             | `/Queues` + `/Members`                                                                           |
| `$client->applications`       | `/Applications`                                                                                  |
| `$client->recordings`         | account-scoped `/Recordings` + WAV fetch                                                         |
| `$client->incomingPhoneNumbers` | `/IncomingPhoneNumbers` — tenant-scoped DID assignment + voice routing                         |
| `$client->diagnostics`        | `/health` + `/openapi.json`                                                                      |

## Twilio drop-in

VoiceML's path layout and field names mirror Twilio. Most Twilio PHP SDK code can be ported by replacing the constructor and the model namespaces:

```php
// Twilio
// $twilio = new Twilio\Rest\Client($sid, $token);
// $call = $twilio->calls->create('+18005551234', '+18005550000', ['url' => '...']);

// VoiceML
$client = new VoiceML\Client(accountSid: $sid, apiKey: $token);
$call = $client->calls->create(new VoiceML\Model\CreateCallRequest(
    to: '+18005551234',
    from: '+18005550000',
    url: 'https://example.com/twiml',
));
```

## Error mapping

All errors extend `VoiceML\Exception\VoiceMLException` (which extends `RuntimeException`):

| HTTP | Exception                                                |
| ---- | -------------------------------------------------------- |
| 400  | `VoiceML\Exception\BadRequestException`                  |
| 401  | `VoiceML\Exception\AuthenticationException`              |
| 403  | `VoiceML\Exception\PermissionDeniedException`            |
| 404  | `VoiceML\Exception\NotFoundException`                    |
| 409  | `VoiceML\Exception\ConflictException`                    |
| 410  | `VoiceML\Exception\GoneException`                        |
| 429  | `VoiceML\Exception\RateLimitException`                   |
| 501  | `VoiceML\Exception\NotImplementedApiException`           |
| 5xx  | `VoiceML\Exception\ServerException`                      |
| any  | `VoiceML\Exception\ApiException` (base for the above)    |

Each `ApiException` carries the parsed Twilio-shape error body (`code`, `message`, `more_info`, `status`) on `->errorCode`, `->getMessage()`, `->getMoreInfo()` / `->moreInfo`, and `->body`.

### Twilio `authToken` alias

For ports from the Twilio PHP SDK, the credential may be passed as `authToken:` instead of `apiKey:` — they are aliases:

```php
$client = new VoiceML\Client(accountSid: $sid, authToken: $token);
```

Passing both raises `ConfigurationException`.

## Recording audio

The audio fetch follows VoiceML's single 302→S3 redirect transparently:

```php
$audio = $client->recordings->getAudio($recordingSid);
file_put_contents("$recordingSid.wav", $audio->content);
```

If the recording is gone (no local file and no S3 key), `GoneException` is raised.

## Pagination

`Calls`, `Conferences`, `Queues`, etc. return Twilio-shape page envelopes. Walk pages manually with `nextPageUri`, or use the helper for `/Calls`:

```php
foreach ($client->calls->iterate(status: 'completed') as $call) {
    echo $call->sid, PHP_EOL;
}
```

## Tests

```bash
composer install
vendor/bin/phpunit
```

## 📖 API Documentation

- **Reference docs:** [voicetel.com/docs/api/v0.6/voiceml/](https://voicetel.com/docs/api/v0.6/voiceml/)
- **Validator:** [voicetel.com/voiceml/validator/](https://voicetel.com/voiceml/validator/)
- **SDK catalogue:** [voicetel.com/docs/voiceml-sdks/](https://voicetel.com/docs/voiceml-sdks/)

## License

MIT with Commons Clause — see [`LICENSE`](LICENSE).
