# Changelog

## [0.9.3] - 2026-10-18

### Breaking changes
- **Removed methods** for endpoints the API no longer serves (405 / error
  20004): `calls->listStreams/getStream/listSiprec/getSiprec/listTranscriptions/getTranscription`
  and their `StreamList` / `SiprecList` models. The start/stop POSTs remain.
- **Pagination**: iterators follow `next_page_uri` / `meta.next_page_url`
  verbatim (with the PageToken) instead of synthesising `Page=N`.
  Page-counting loops never terminated for Calls and Conferences, and
  Messages/Recordings rejected `Page>0` without a token. `total` and
  `num_pages` were removed from list envelopes.
- **Retry policy**: non-idempotent POSTs are no longer retried on 5xx or
  timeouts (duplicate calls/messages prevention); they retry only on 429
  and connection-establishment failures.
- **List parameters typed**: `calls->list()`, the iterate helpers and the
  Conversations/Assistants/Voice/Pricing lists take typed parameter
  objects / typed arrays instead of loose named arguments; non-spec
  `StartTime>=` / `StartTime<=` filters are removed (use `startTime` /
  `startTimeLt` / `startTimeGt`).
- **IncomingPhoneNumbers**: create/update no longer send `FriendlyName`;
  the Local/Mobile/TollFree creates send only `PhoneNumber` /
  `VoiceUrl` / `VoiceMethod`; `capabilities` is nullable and absent
  flags decode as `false` instead of throwing.
- **Exceptions**: network failures raise `ConnectionException`
  (a `VoiceMLException`); 2xx decode failures say "could not decode"
  and keep the cause.

### Added
- **Form encoding fix (critical)**: list-valued parameters are sent as
  repeated keys (`StatusCallbackEvent=a&StatusCallbackEvent=b`), not
  `Key[0]=…`, which the server silently dropped — status-callback event
  masks on `POST /Calls`, role `Permission`, conversation `Participant`
  and webhook `Filters` now reach the server.
- 36 previously missing operations: Accounts fetch/update + **key
  rotation** (`rotateKey()`), Balance, OutgoingCallerIds, Message Media,
  Exports, account and recording Transcriptions, conference recording
  start, Usage Records (+8 windows), SIP Registrations, Messaging
  Service sender phone numbers, and openapi.yaml/yml diagnostics.
- **Monitor alerts** (`monitorAlerts`): `list()` / `fetch()` / `nextPage()`
  / `iterate()` for `GET /v1/Alerts`.
- **Message feedback** (`messages->createFeedback()`).
- `RequestValidator` for `X-Twilio-Signature` webhook verification.
- MMS (`mediaUrl`), `ValidityPeriod`, `StatusCallbackMethod` on message
  create; the `Sms*` webhook fields + `SmsApplicationSid` on
  IncomingPhoneNumbers; Application SMS callbacks; `RecordingTrack` /
  `PauseBehavior`; SIP list `FriendlyName` filters; typed `Notification`
  and `CallEvent` models.
- Spec-driven contract test (333/333 operations) against the vendored
  public spec; `phpstan` (level 5) in CI with zero findings; PHPUnit +
  phpstan matrices across PHP 8.1–8.4 and Guzzle 7/8.

### Fixed
- Path segments percent-encoded (`+` as `%2B`); `?`/`#`/`../` can no
  longer rewrite the request URL.
- Recording-audio and export downloads follow redirects manually,
  dropping Authorization on host changes and refusing https→http.
- `Retry-After` honoured as seconds or HTTP-date, capped at 60 s;
  the rate-limit exception exposes the parsed delay.
- Timeouts apply even with an injected HTTP client; `allow_redirects`
  is handled by the SDK.
- API key excluded from `var_dump`/`print_r` via `__debugInfo` and
  `#[\SensitiveParameter]`; Guzzle 8 exception hierarchy handled
  (`RequestException::getResponse()` no longer assumed).
- Diagnostics reuse the client's HTTP client and timeout and work
  without credentials; 503 maps to `ServerException`.
- `.gitattributes` export-ignore (tests/CI no longer shipped in dist);
  the hard-coded `version` field is removed from composer.json;
  homepage points at the API docs.

### Security
- Retry defaults no longer replay non-idempotent requests after
  timeouts or 5xx responses.

### Docs
- Fictional `555-0100`-range example numbers throughout; SECURITY.md
  supported-versions table updated to 0.9.x.
