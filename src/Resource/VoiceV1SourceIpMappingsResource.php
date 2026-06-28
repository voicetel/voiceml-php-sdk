<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1SourceIpMappingRequest;
use VoiceML\Model\UpdateVoiceV1SourceIpMappingRequest;
use VoiceML\Model\VoiceV1SourceIpMapping;
use VoiceML\Model\VoiceV1SourceIpMappingList;
use VoiceML\Transport;

/** `/v1/SourceIpMappings` — Twilio Voice v1 SourceIpMapping. */
final class VoiceV1SourceIpMappingsResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateVoiceV1SourceIpMappingRequest $body */
    public function create(array|CreateVoiceV1SourceIpMappingRequest $body): VoiceV1SourceIpMapping
    {
        $form = $body instanceof CreateVoiceV1SourceIpMappingRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/SourceIpMappings', null, $form);
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1SourceIpMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/SourceIpMappings', $query);
        return VoiceV1SourceIpMappingList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1SourceIpMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/SourceIpMappings/{$sid}");
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1SourceIpMappingRequest $body */
    public function update(string $sid, array|UpdateVoiceV1SourceIpMappingRequest $body): VoiceV1SourceIpMapping
    {
        $form = $body instanceof UpdateVoiceV1SourceIpMappingRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/SourceIpMappings/{$sid}", null, $form);
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/SourceIpMappings/{$sid}");
    }
}
