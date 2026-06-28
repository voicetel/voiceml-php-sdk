<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\CreateVoiceV1ConnectionPolicyRequest;
use VoiceML\Model\UpdateVoiceV1ConnectionPolicyRequest;
use VoiceML\Model\VoiceV1ConnectionPolicy;
use VoiceML\Model\VoiceV1ConnectionPolicyList;
use VoiceML\Transport;

/** `/v1/ConnectionPolicies` — Twilio Voice v1 ConnectionPolicies. */
final class VoiceV1ConnectionPoliciesResource
{
    public function __construct(private readonly Transport $transport)
    {
    }

    /** @param array<string,mixed>|CreateVoiceV1ConnectionPolicyRequest $body */
    public function create(array|CreateVoiceV1ConnectionPolicyRequest $body = []): VoiceV1ConnectionPolicy
    {
        $form = $body instanceof CreateVoiceV1ConnectionPolicyRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', '/v1/ConnectionPolicies', null, $form);
        return VoiceV1ConnectionPolicy::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): VoiceV1ConnectionPolicyList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', '/v1/ConnectionPolicies', $query);
        return VoiceV1ConnectionPolicyList::fromArray($raw);
    }

    public function fetch(string $sid): VoiceV1ConnectionPolicy
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', "/v1/ConnectionPolicies/{$sid}");
        return VoiceV1ConnectionPolicy::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1ConnectionPolicyRequest $body */
    public function update(string $sid, array|UpdateVoiceV1ConnectionPolicyRequest $body = []): VoiceV1ConnectionPolicy
    {
        $form = $body instanceof UpdateVoiceV1ConnectionPolicyRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', "/v1/ConnectionPolicies/{$sid}", null, $form);
        return VoiceV1ConnectionPolicy::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', "/v1/ConnectionPolicies/{$sid}");
    }

    /**
     * Sub-collection: `$client->voiceV1->connectionPolicies($parentSid)->targets`.
     *
     * Bind a parent ConnectionPolicy sid and return a {@see VoiceV1ConnectionPolicyTargetsResource}
     * that operates under `/v1/ConnectionPolicies/{Sid}/Targets`.
     */
    public function targets(string $connectionPolicySid): VoiceV1ConnectionPolicyTargetsResource
    {
        return new VoiceV1ConnectionPolicyTargetsResource($this->transport, $connectionPolicySid);
    }
}
