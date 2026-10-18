<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1SourceIpMappingRequest;
use VoiceML\Model\UpdateVoiceV1SourceIpMappingRequest;
use VoiceML\Model\VoiceV1SourceIpMapping;
use VoiceML\Model\VoiceV1SourceIpMappingList;

/** `/v1/SourceIpMappings` — Twilio Voice v1 SourceIpMapping. */
final class VoiceV1SourceIpMappingsResource extends Resource
{

    /** @param array<string,mixed>|CreateVoiceV1SourceIpMappingRequest $body */
    public function create(array|CreateVoiceV1SourceIpMappingRequest $body): VoiceV1SourceIpMapping
    {
        $form = $body instanceof CreateVoiceV1SourceIpMappingRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('SourceIpMappings'), null, $form);
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1SourceIpMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('SourceIpMappings'), $query);
        return VoiceV1SourceIpMappingList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1SourceIpMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('SourceIpMappings', $sid));
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1SourceIpMappingRequest $body */
    public function update(string $sid, array|UpdateVoiceV1SourceIpMappingRequest $body): VoiceV1SourceIpMapping
    {
        $form = $body instanceof UpdateVoiceV1SourceIpMappingRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('SourceIpMappings', $sid), null, $form);
        return VoiceV1SourceIpMapping::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('SourceIpMappings', $sid));
    }
}
