<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1IpRecordRequest;
use VoiceML\Model\UpdateVoiceV1IpRecordRequest;
use VoiceML\Model\VoiceV1IpRecord;
use VoiceML\Model\VoiceV1IpRecordList;
use VoiceML\Transport;

/**
 * `/v1/IpRecords` — Twilio Voice v1 IpRecord. The /v1/ namespace bypasses
 * the /2010-04-01/Accounts/{Sid}/ prefix; the account is resolved from
 * HTTP Basic auth.
 */
final class VoiceV1IpRecordsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateVoiceV1IpRecordRequest $body */
    public function create(array|CreateVoiceV1IpRecordRequest $body): VoiceV1IpRecord
    {
        $form = $body instanceof CreateVoiceV1IpRecordRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/IpRecords', null, $form);
        return VoiceV1IpRecord::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1IpRecordList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/IpRecords', $query);
        return VoiceV1IpRecordList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1IpRecord
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/IpRecords/{$sid}");
        return VoiceV1IpRecord::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1IpRecordRequest $body */
    public function update(string $sid, array|UpdateVoiceV1IpRecordRequest $body = []): VoiceV1IpRecord
    {
        $form = $body instanceof UpdateVoiceV1IpRecordRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/IpRecords/{$sid}", null, $form);
        return VoiceV1IpRecord::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/IpRecords/{$sid}");
    }
}
