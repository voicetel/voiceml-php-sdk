<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1ByocTrunkRequest;
use VoiceML\Model\UpdateVoiceV1ByocTrunkRequest;
use VoiceML\Model\VoiceV1ByocTrunk;
use VoiceML\Model\VoiceV1ByocTrunkList;

/** `/v1/ByocTrunks` — Twilio Voice v1 bring-your-own-carrier trunks. */
final class VoiceV1ByocTrunksResource extends Resource
{

    /** @param array<string,mixed>|CreateVoiceV1ByocTrunkRequest $body */
    public function create(array|CreateVoiceV1ByocTrunkRequest $body = []): VoiceV1ByocTrunk
    {
        $form = $body instanceof CreateVoiceV1ByocTrunkRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('ByocTrunks'), null, $form);
        return VoiceV1ByocTrunk::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1ByocTrunkList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('ByocTrunks'), $query);
        return VoiceV1ByocTrunkList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1ByocTrunk
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('ByocTrunks', $sid));
        return VoiceV1ByocTrunk::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1ByocTrunkRequest $body */
    public function update(string $sid, array|UpdateVoiceV1ByocTrunkRequest $body = []): VoiceV1ByocTrunk
    {
        $form = $body instanceof UpdateVoiceV1ByocTrunkRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('ByocTrunks', $sid), null, $form);
        return VoiceV1ByocTrunk::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('ByocTrunks', $sid));
    }
}
