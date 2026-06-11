<?php

declare(strict_types=1);

namespace VoiceML\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use VoiceML\Model\Application;
use VoiceML\Model\ApplicationList;
use VoiceML\Model\Call;
use VoiceML\Model\CallList;
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
     * Operation IDs with no SDK model — notifications/events compat stubs and
     * UserDefinedMessage. Messages were skipped until v0.7.0 added the
     * `Message`/`MessageList` models.
     */
    private const SKIP_OPS = [
        'ListCallEvent' => true,
        'ListCallNotification' => true,
        'FetchCallNotification' => true,
        'ListNotification' => true,
        'FetchNotification' => true,
        'CreateUserDefinedMessage' => true,
    ];

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

            default:
                self::fail("conformance harness: no mapping for operation_id={$opId} (case={$caseName}). Add a case or extend SKIP_OPS.");
        }

        // Suppress static-analysis "unused variable" warning — we use $v
        // for the side-effect of construction (catches type errors).
        unset($v);
    }

    public function testSkipsWhenEnvUnset(): void
    {
        if (getenv(self::FIXTURES_ENV) !== false && getenv(self::FIXTURES_ENV) !== '') {
            self::markTestSkipped(self::FIXTURES_ENV . ' is set; the data-driven tests cover this path');
        }
        self::assertTrue(true, 'Sentinel: harness wires up without an env var.');
    }
}
