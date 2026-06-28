<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VoiceML\Model\Application;
use VoiceML\Model\ApplicationList;
use VoiceML\Model\Call;
use VoiceML\Model\CallList;
use VoiceML\Model\CallPayment;
use VoiceML\Model\CallTranscription;
use VoiceML\Model\Conference;
use VoiceML\Model\ConferenceList;
use VoiceML\Model\IncomingPhoneNumber;
use VoiceML\Model\IncomingPhoneNumberList;
use VoiceML\Model\Message;
use VoiceML\Model\MessageList;
use VoiceML\Model\Participant;
use VoiceML\Model\ParticipantList;
use VoiceML\Model\Queue;
use VoiceML\Model\QueueList;
use VoiceML\Model\QueueMember;
use VoiceML\Model\QueueMemberList;
use VoiceML\Model\Recording;
use VoiceML\Model\RecordingList;
use VoiceML\Model\SipCredential;
use VoiceML\Model\SipCredentialList;
use VoiceML\Model\SipCredentialListList;
use VoiceML\Model\SipCredentialListMappingList;
use VoiceML\Model\SipCredentialListPage;
use VoiceML\Model\SipDomain;
use VoiceML\Model\SipDomainList;
use VoiceML\Model\SipDomainMapping;
use VoiceML\Model\SipIpAccessControlList;
use VoiceML\Model\SipIpAccessControlListList;
use VoiceML\Model\SipIpAccessControlListMappingList;
use VoiceML\Model\SipIpAddress;
use VoiceML\Model\SipIpAddressList;
use VoiceML\Model\SiprecSession;
use VoiceML\Model\Stream;

/**
 * Twilio response-shape conformance tests (#330 Phase C). Mirrors the
 * Go (voiceml-go-sdk@d6ac75c), Python (voiceml-python-sdk), TypeScript
 * (voiceml-node-sdk@a11b0a1), Java (voiceml-java-sdk@9178659), and C#
 * (voiceml-csharp-sdk@087679f) harnesses: load 132 canonical Twilio
 * response examples from callBroadcast's
 * cmd/twilio-conformance-fixtures, run each through the matching SDK
 * model's `fromArray()` factory, assert key fields. SKIPPED unless
 * VOICEML_CONFORMANCE_FIXTURES env points at the corpus.
 *
 * PHP doesn't have strict-typed JSON deserialization the way Go/Java
 * do — `fromArray()` is the SDK's canonical decoder, and it enforces
 * the typed model constructor (PHP 8.1 readonly properties + nullable
 * union types). A type mismatch trips a TypeError at construction;
 * required-field enforcement is in the post-decode assertNotEmpty
 * calls below.
 *
 * Run:
 *
 *   VOICEML_CONFORMANCE_FIXTURES=/path/to/callBroadcast/cmd/twilio-conformance-fixtures/fixtures \
 *     ./vendor/bin/phpunit --filter ConformanceTest
 */
final class ConformanceTest extends TestCase
{
    private const FIXTURES_ENV = 'VOICEML_CONFORMANCE_FIXTURES';

    /**
     * Operation IDs the harness should skip outright. Empty in current state:
     * every operation in the fixture corpus is dispatched, either to a typed
     * SDK model or (for resources the PHP SDK doesn't yet expose as DTOs) to
     * a raw-array shape check. Messages were skipped until v0.7.0 added the
     * `Message`/`MessageList` models; notifications/events/UserDefinedMessage
     * are now validated as raw response shapes (mirrors Java's JsonNode and
     * Go's `&map[string]any{}` dispatch).
     */
    private const SKIP_OPS = [];

    /**
     * Sentinel op id used when the fixtures env is unset — guarantees a non-empty data set so
     * PHPUnit 10's strict empty-provider check doesn't error on the unconfigured-CI path.
     */
    private const SENTINEL_OP = '__sentinel_env_unset__';

    /**
     * @return iterable<string, array{0:string, 1:string, 2:string}>
     */
    public static function fixtureProvider(): iterable
    {
        $root = getenv(self::FIXTURES_ENV);
        if ($root === false || $root === '') {
            yield 'env-unset-sentinel' => [self::SENTINEL_OP, 'env-unset-sentinel', ''];
            return;
        }
        $indexPath = $root . DIRECTORY_SEPARATOR . 'index.json';
        if (!is_file($indexPath)) {
            yield 'index-missing-sentinel' => [self::SENTINEL_OP, 'index-missing-sentinel', ''];
            return;
        }
        /** @var array<int,array{resource:string,operation_id:string,example_name:string,file:string}> $entries */
        $entries = json_decode((string) file_get_contents($indexPath), true, flags: JSON_THROW_ON_ERROR);
        foreach ($entries as $entry) {
            $opId = (string) $entry['operation_id'];
            $name = $entry['resource'] . '/' . $opId . '/' . ($entry['example_name'] ?? '');
            $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, (string) $entry['file']);
            yield $name => [$opId, $name, $path];
        }
    }

    #[DataProvider('fixtureProvider')]
    public function testFixtureConforms(string $opId, string $caseName, string $fixturePath): void
    {
        if ($opId === self::SENTINEL_OP) {
            $this->markTestSkipped(self::FIXTURES_ENV . ' is unset; conformance corpus not loaded');
        }
        if (isset(self::SKIP_OPS[$opId])) {
            $this->markTestSkipped("no SDK model for {$opId}");
        }
        $body = (string) file_get_contents($fixturePath);
        /** @var array<string,mixed> $data */
        $data = json_decode($body, true, flags: JSON_THROW_ON_ERROR);

        switch ($opId) {
            case 'CreateCall':
            case 'FetchCall':
            case 'UpdateCall':
                $v = Call::fromArray($data);
                self::assertNotEmpty($v->sid, 'Call.sid');
                self::assertNotEmpty($v->accountSid, 'Call.account_sid');
                break;

            case 'ListCall':
                $v = CallList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'CallList.uri');
                break;

            case 'FetchConference':
            case 'UpdateConference':
                $v = Conference::fromArray($data);
                self::assertNotEmpty($v->sid, 'Conference.sid');
                self::assertNotEmpty($v->accountSid, 'Conference.account_sid');
                break;

            case 'ListConference':
                $v = ConferenceList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'ConferenceList.uri');
                break;

            case 'CreateParticipant':
            case 'FetchParticipant':
            case 'UpdateParticipant':
                $v = Participant::fromArray($data);
                self::assertNotEmpty($v->callSid, 'Participant.call_sid');
                self::assertNotEmpty($v->conferenceSid, 'Participant.conference_sid');
                break;

            case 'ListParticipant':
                $v = ParticipantList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'ParticipantList.uri');
                break;

            case 'CreateQueue':
            case 'FetchQueue':
            case 'UpdateQueue':
                $v = Queue::fromArray($data);
                self::assertNotEmpty($v->sid, 'Queue.sid');
                self::assertNotEmpty($v->accountSid, 'Queue.account_sid');
                break;

            case 'ListQueue':
                $v = QueueList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'QueueList.uri');
                break;

            case 'FetchMember':
            case 'UpdateMember':
                $v = QueueMember::fromArray($data);
                self::assertNotEmpty($v->callSid, 'QueueMember.call_sid');
                break;

            case 'ListMember':
                $v = QueueMemberList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'QueueMemberList.uri');
                break;

            case 'CreateApplication':
            case 'FetchApplication':
            case 'UpdateApplication':
                $v = Application::fromArray($data);
                self::assertNotEmpty($v->sid, 'Application.sid');
                self::assertNotEmpty($v->accountSid, 'Application.account_sid');
                break;

            case 'ListApplication':
                $v = ApplicationList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'ApplicationList.uri');
                break;

            case 'CreateCallRecording':
            case 'FetchCallRecording':
            case 'UpdateCallRecording':
            case 'FetchRecording':
            case 'FetchConferenceRecording':
            case 'UpdateConferenceRecording':
                $v = Recording::fromArray($data);
                self::assertNotEmpty($v->sid, 'Recording.sid');
                self::assertNotEmpty($v->accountSid, 'Recording.account_sid');
                break;

            case 'ListCallRecording':
            case 'ListRecording':
            case 'ListConferenceRecording':
                $v = RecordingList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'RecordingList.uri');
                break;

            case 'CreateIncomingPhoneNumber':
            case 'CreateIncomingPhoneNumberLocal':
            case 'CreateIncomingPhoneNumberMobile':
            case 'CreateIncomingPhoneNumberTollFree':
            case 'FetchIncomingPhoneNumber':
            case 'UpdateIncomingPhoneNumber':
                $v = IncomingPhoneNumber::fromArray($data);
                self::assertNotEmpty($v->sid, 'IncomingPhoneNumber.sid');
                self::assertNotEmpty($v->accountSid, 'IncomingPhoneNumber.account_sid');
                break;

            case 'ListIncomingPhoneNumber':
            case 'ListIncomingPhoneNumberLocal':
            case 'ListIncomingPhoneNumberMobile':
            case 'ListIncomingPhoneNumberTollFree':
                $v = IncomingPhoneNumberList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'IncomingPhoneNumberList.uri');
                break;

            // Stream / SiprecSession / CallTranscription Create/Update fixtures don't
            // emit api_version; same drift the TS harness fixed-forward by relaxing
            // the field. Sid/AccountSid/CallSid asserted; api_version skipped.
            case 'CreateStream':
            case 'UpdateStream':
                $v = Stream::fromArray($data);
                self::assertNotEmpty($v->sid, 'Stream.sid');
                self::assertNotEmpty($v->accountSid, 'Stream.account_sid');
                self::assertNotEmpty($v->callSid, 'Stream.call_sid');
                break;

            case 'CreateSiprec':
            case 'UpdateSiprec':
                $v = SiprecSession::fromArray($data);
                self::assertNotEmpty($v->sid, 'SiprecSession.sid');
                self::assertNotEmpty($v->accountSid, 'SiprecSession.account_sid');
                self::assertNotEmpty($v->callSid, 'SiprecSession.call_sid');
                break;

            case 'CreateRealtimeTranscription':
            case 'UpdateRealtimeTranscription':
                $v = CallTranscription::fromArray($data);
                self::assertNotEmpty($v->sid, 'CallTranscription.sid');
                self::assertNotEmpty($v->accountSid, 'CallTranscription.account_sid');
                self::assertNotEmpty($v->callSid, 'CallTranscription.call_sid');
                break;

            case 'CreateMessage':
            case 'FetchMessage':
            case 'UpdateMessage':
                $v = Message::fromArray($data);
                self::assertNotEmpty($v->sid, 'Message.sid');
                self::assertNotEmpty($v->accountSid, 'Message.account_sid');
                break;

            case 'ListMessage':
                $v = MessageList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'MessageList.uri');
                break;

            // ---------- Payments (`/Calls/{sid}/Payments`) ----------
            // CreatePayments/UpdatePayments fixtures omit api_version; the
            // CallPayment model tolerates that (constructor defaults to '').
            case 'CreatePayments':
            case 'UpdatePayments':
                $v = CallPayment::fromArray($data);
                self::assertNotEmpty($v->sid, 'CallPayment.sid');
                self::assertNotEmpty($v->accountSid, 'CallPayment.account_sid');
                self::assertNotEmpty($v->callSid, 'CallPayment.call_sid');
                break;

            // ---------- SIP Domains ----------
            case 'CreateSipDomain':
            case 'FetchSipDomain':
            case 'UpdateSipDomain':
                $v = SipDomain::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipDomain.sid');
                self::assertNotEmpty($v->accountSid, 'SipDomain.account_sid');
                self::assertNotEmpty($v->domainName, 'SipDomain.domain_name');
                break;

            case 'ListSipDomain':
                $v = SipDomainList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipDomainList.uri');
                break;

            // ---------- SIP CredentialLists ----------
            case 'CreateSipCredentialList':
            case 'FetchSipCredentialList':
            case 'UpdateSipCredentialList':
                $v = SipCredentialList::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipCredentialList.sid');
                self::assertNotEmpty($v->accountSid, 'SipCredentialList.account_sid');
                break;

            case 'ListSipCredentialList':
                $v = SipCredentialListList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipCredentialListList.uri');
                break;

            // ---------- SIP Credentials (inside a CredentialList) ----------
            case 'CreateSipCredential':
            case 'FetchSipCredential':
            case 'UpdateSipCredential':
                $v = SipCredential::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipCredential.sid');
                self::assertNotEmpty($v->accountSid, 'SipCredential.account_sid');
                self::assertNotEmpty($v->credentialListSid, 'SipCredential.credential_list_sid');
                break;

            case 'ListSipCredential':
                $v = SipCredentialListPage::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipCredentialListPage.uri');
                break;

            // ---------- SIP IpAccessControlLists ----------
            case 'CreateSipIpAccessControlList':
            case 'FetchSipIpAccessControlList':
            case 'UpdateSipIpAccessControlList':
                $v = SipIpAccessControlList::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipIpAccessControlList.sid');
                self::assertNotEmpty($v->accountSid, 'SipIpAccessControlList.account_sid');
                break;

            case 'ListSipIpAccessControlList':
                $v = SipIpAccessControlListList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipIpAccessControlListList.uri');
                break;

            // ---------- SIP IpAddresses (inside an IpAccessControlList) ----------
            case 'CreateSipIpAddress':
            case 'FetchSipIpAddress':
            case 'UpdateSipIpAddress':
                $v = SipIpAddress::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipIpAddress.sid');
                self::assertNotEmpty($v->accountSid, 'SipIpAddress.account_sid');
                self::assertNotEmpty($v->ipAddress, 'SipIpAddress.ip_address');
                break;

            case 'ListSipIpAddress':
                $v = SipIpAddressList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipIpAddressList.uri');
                break;

            // ---------- SIP CredentialListMappings (historical /Domains/{SD}/CredentialListMappings) ----------
            case 'CreateSipCredentialListMapping':
            case 'FetchSipCredentialListMapping':
                $v = SipDomainMapping::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipDomainMapping.sid');
                self::assertNotEmpty($v->accountSid, 'SipDomainMapping.account_sid');
                break;

            case 'ListSipCredentialListMapping':
                $v = SipCredentialListMappingList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipCredentialListMappingList.uri');
                break;

            // ---------- SIP IpAccessControlListMappings (historical /Domains/{SD}/IpAccessControlListMappings) ----------
            case 'CreateSipIpAccessControlListMapping':
            case 'FetchSipIpAccessControlListMapping':
                $v = SipDomainMapping::fromArray($data);
                self::assertNotEmpty($v->sid, 'SipDomainMapping.sid');
                self::assertNotEmpty($v->accountSid, 'SipDomainMapping.account_sid');
                break;

            case 'ListSipIpAccessControlListMapping':
                $v = SipIpAccessControlListMappingList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', 'SipIpAccessControlListMappingList.uri');
                break;

            // ---------- SIP Auth/Calls + Auth/Registrations mappings (v0.8 split surfaces) ----------
            // The Twilio Auth-namespaced mapping fixtures omit domain_sid on
            // Create/Fetch (the binding is implicit in the URL); assert only
            // the universally-present sid + account_sid. Mirrors Java/C#.
            case 'CreateSipAuthCallsCredentialListMapping':
            case 'FetchSipAuthCallsCredentialListMapping':
            case 'CreateSipAuthCallsIpAccessControlListMapping':
            case 'FetchSipAuthCallsIpAccessControlListMapping':
            case 'CreateSipAuthRegistrationsCredentialListMapping':
            case 'FetchSipAuthRegistrationsCredentialListMapping':
                $v = SipDomainMapping::fromArray($data);
                self::assertNotEmpty($v->sid, "SipDomainMapping.sid ({$opId})");
                self::assertNotEmpty($v->accountSid, "SipDomainMapping.account_sid ({$opId})");
                break;

            // The Auth/* list envelopes use the generic `contents` key instead
            // of `credential_list_mappings` / `ip_access_control_list_mappings`.
            // The List models tolerate the missing key (items stay empty), and
            // the wire `uri` envelope field is preserved — that's what we assert.
            case 'ListSipAuthCallsCredentialListMapping':
            case 'ListSipAuthRegistrationsCredentialListMapping':
                $v = SipCredentialListMappingList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', "SipCredentialListMappingList.uri ({$opId})");
                break;

            case 'ListSipAuthCallsIpAccessControlListMapping':
                $v = SipIpAccessControlListMappingList::fromArray($data);
                self::assertNotEmpty($v->uri ?? '', "SipIpAccessControlListMappingList.uri ({$opId})");
                break;

            // ---------- Resources not (yet) modelled as PHP DTOs ----------
            // Mirrors the Java harness's JsonNode dispatch and the Go harness's
            // `&map[string]any{}` target: assert the documented top-level fields
            // on the raw decoded array. Catches malformed responses and shape
            // drift on key fields without forcing a full DTO surface for
            // resources the PHP SDK doesn't (yet) expose to callers.

            case 'FetchAccount':
            case 'UpdateAccount':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'Account.sid');
                self::assertNotEmpty((string) ($data['status'] ?? ''), 'Account.status');
                self::assertNotEmpty((string) ($data['uri'] ?? ''), 'Account.uri');
                break;

            case 'FetchBalance':
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'Balance.account_sid');
                self::assertNotEmpty((string) ($data['balance'] ?? ''), 'Balance.balance');
                self::assertNotEmpty((string) ($data['currency'] ?? ''), 'Balance.currency');
                break;

            case 'FetchMedia':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'Media.sid');
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'Media.account_sid');
                self::assertNotEmpty((string) ($data['parent_sid'] ?? ''), 'Media.parent_sid');
                self::assertNotEmpty((string) ($data['content_type'] ?? ''), 'Media.content_type');
                break;

            case 'ListMedia':
                self::assertNotEmpty((string) ($data['uri'] ?? ''), 'MediaList.uri');
                self::assertArrayHasKey('media_list', $data, 'MediaList.media_list (envelope key)');
                break;

            case 'FetchOutgoingCallerId':
            case 'UpdateOutgoingCallerId':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'OutgoingCallerId.sid');
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'OutgoingCallerId.account_sid');
                self::assertNotEmpty((string) ($data['phone_number'] ?? ''), 'OutgoingCallerId.phone_number');
                break;

            case 'ListOutgoingCallerId':
                self::assertNotEmpty((string) ($data['uri'] ?? ''), 'OutgoingCallerIdList.uri');
                self::assertArrayHasKey('outgoing_caller_ids', $data, 'OutgoingCallerIdList.outgoing_caller_ids (envelope key)');
                break;

            case 'CreateValidationRequest':
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'ValidationRequest.account_sid');
                self::assertNotEmpty((string) ($data['phone_number'] ?? ''), 'ValidationRequest.phone_number');
                self::assertNotEmpty((string) ($data['validation_code'] ?? ''), 'ValidationRequest.validation_code');
                break;

            // Classic /Transcriptions resource (recording transcriptions, NOT the
            // realtime CallTranscription). The PHP SDK doesn't currently expose
            // this as a DTO; assert documented top-level fields on the raw array.
            case 'FetchTranscription':
            case 'FetchRecordingTranscription':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'Transcription.sid');
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'Transcription.account_sid');
                self::assertNotEmpty((string) ($data['recording_sid'] ?? ''), 'Transcription.recording_sid');
                break;

            case 'ListTranscription':
            case 'ListRecordingTranscription':
                self::assertNotEmpty((string) ($data['uri'] ?? ''), "TranscriptionList.uri ({$opId})");
                self::assertArrayHasKey('transcriptions', $data, 'TranscriptionList.transcriptions (envelope key)');
                break;

            // ---------- Notifications / Events / UserDefinedMessage compat stubs ----------
            // VoiceML treats these as Twilio-compat surface area that VoiceML
            // itself doesn't populate (notifications/events are first-class on
            // Twilio; VoiceML returns empty lists). The fixtures are still the
            // canonical Twilio shape — assert documented top-level fields.

            case 'FetchCallNotification':
            case 'FetchNotification':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'Notification.sid');
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'Notification.account_sid');
                self::assertNotEmpty((string) ($data['call_sid'] ?? ''), 'Notification.call_sid');
                self::assertNotEmpty((string) ($data['uri'] ?? ''), 'Notification.uri');
                break;

            case 'ListCallNotification':
            case 'ListNotification':
                self::assertNotEmpty((string) ($data['uri'] ?? ''), "NotificationList.uri ({$opId})");
                self::assertArrayHasKey('notifications', $data, 'NotificationList.notifications (envelope key)');
                break;

            case 'ListCallEvent':
                self::assertNotEmpty((string) ($data['uri'] ?? ''), 'EventsList.uri');
                self::assertArrayHasKey('events', $data, 'EventsList.events (envelope key)');
                break;

            // UserDefinedMessage Create response has sid/account_sid/call_sid/
            // date_created but no `uri`. KX-prefixed sid.
            case 'CreateUserDefinedMessage':
                self::assertNotEmpty((string) ($data['sid'] ?? ''), 'UserDefinedMessage.sid');
                self::assertNotEmpty((string) ($data['account_sid'] ?? ''), 'UserDefinedMessage.account_sid');
                self::assertNotEmpty((string) ($data['call_sid'] ?? ''), 'UserDefinedMessage.call_sid');
                break;

            default:
                self::fail("conformance harness: no mapping for operation_id={$opId} (case={$caseName}). Add a case or extend SKIP_OPS.");
        }

        // Suppress static-analysis "unused variable" warning — we use $v
        // for the side-effect of construction (catches type errors). The
        // raw-JSON dispatch branches above don't set $v; unset() of an
        // undefined variable is a silent no-op in PHP.
        unset($v);
    }

    /**
     * Sentinel test verifying the harness wires up cleanly in both
     * fixture-mounted and unmounted contexts:
     *   - env unset: fixtureProvider yields exactly one sentinel row, which
     *     testFixtureConforms() marks as a soft skip (not counted toward the
     *     conformance pass).
     *   - env set: fixtureProvider yields the full fixture corpus; this
     *     sentinel just confirms the provider produced more than the
     *     unmounted-path single row.
     * Asserting concretely (instead of conditionally skipping) keeps the
     * conformance suite at zero skips when the corpus is mounted.
     */
    public function testProviderWiresUp(): void
    {
        $rows = iterator_to_array(self::fixtureProvider(), preserve_keys: false);
        self::assertNotEmpty($rows, 'fixtureProvider must yield at least the sentinel row');
        $envSet = getenv(self::FIXTURES_ENV) !== false && getenv(self::FIXTURES_ENV) !== '';
        if ($envSet) {
            self::assertGreaterThan(
                1,
                count($rows),
                'with VOICEML_CONFORMANCE_FIXTURES set, provider should yield the full corpus, not just the sentinel',
            );
        } else {
            self::assertCount(
                1,
                $rows,
                'with VOICEML_CONFORMANCE_FIXTURES unset, provider should yield only the sentinel row',
            );
            self::assertSame(self::SENTINEL_OP, $rows[0][0], 'sentinel row op id mismatch');
        }
    }
}
