<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1ConnectionPolicyTargetRequest;
use VoiceML\Model\UpdateVoiceV1ConnectionPolicyTargetRequest;
use VoiceML\Model\VoiceV1ConnectionPolicyTarget;
use VoiceML\Model\VoiceV1ConnectionPolicyTargetList;
use VoiceML\Transport;

/**
 * `/v1/ConnectionPolicies/{ConnectionPolicySid}/Targets` — operations bound
 * to a parent ConnectionPolicy. Instances are short-lived; created via
 * {@see VoiceV1ConnectionPoliciesResource::targets()} so the parent sid is
 * captured in one place.
 */
final class VoiceV1ConnectionPolicyTargetsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $connectionPolicySid,
    ) {
    }

    /** @param array<string,mixed>|CreateVoiceV1ConnectionPolicyTargetRequest $body */
    public function create(array|CreateVoiceV1ConnectionPolicyTargetRequest $body): VoiceV1ConnectionPolicyTarget
    {
        $form = $body instanceof CreateVoiceV1ConnectionPolicyTargetRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/ConnectionPolicies/{$this->connectionPolicySid}/Targets", null, $form);
        return VoiceV1ConnectionPolicyTarget::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1ConnectionPolicyTargetList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/ConnectionPolicies/{$this->connectionPolicySid}/Targets", $query);
        return VoiceV1ConnectionPolicyTargetList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1ConnectionPolicyTarget
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/ConnectionPolicies/{$this->connectionPolicySid}/Targets/{$sid}");
        return VoiceV1ConnectionPolicyTarget::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1ConnectionPolicyTargetRequest $body */
    public function update(string $sid, array|UpdateVoiceV1ConnectionPolicyTargetRequest $body = []): VoiceV1ConnectionPolicyTarget
    {
        $form = $body instanceof UpdateVoiceV1ConnectionPolicyTargetRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/ConnectionPolicies/{$this->connectionPolicySid}/Targets/{$sid}", null, $form);
        return VoiceV1ConnectionPolicyTarget::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/ConnectionPolicies/{$this->connectionPolicySid}/Targets/{$sid}");
    }
}
