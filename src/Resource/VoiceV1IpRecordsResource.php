<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1IpRecordRequest;
use VoiceML\Model\UpdateVoiceV1IpRecordRequest;
use VoiceML\Model\VoiceV1IpRecord;
use VoiceML\Model\VoiceV1IpRecordList;

/**
 * `/v1/IpRecords` — Twilio Voice v1 IpRecord. The /v1/ namespace bypasses
 * the /2010-04-01/Accounts/{Sid}/ prefix; the account is resolved from
 * HTTP Basic auth.
 */
final class VoiceV1IpRecordsResource extends Resource
{

    /** @param array<string,mixed>|CreateVoiceV1IpRecordRequest $body */
    public function create(array|CreateVoiceV1IpRecordRequest $body): VoiceV1IpRecord
    {
        $form = $body instanceof CreateVoiceV1IpRecordRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('IpRecords'), null, $form);
        return VoiceV1IpRecord::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1IpRecordList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('IpRecords'), $query);
        return VoiceV1IpRecordList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1IpRecord
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('IpRecords', $sid));
        return VoiceV1IpRecord::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1IpRecordRequest $body */
    public function update(string $sid, array|UpdateVoiceV1IpRecordRequest $body = []): VoiceV1IpRecord
    {
        $form = $body instanceof UpdateVoiceV1IpRecordRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('IpRecords', $sid), null, $form);
        return VoiceV1IpRecord::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('IpRecords', $sid));
    }
}
