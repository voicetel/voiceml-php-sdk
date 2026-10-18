<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1Credential;
use VoiceML\Model\ConversationsV1CredentialList;
use VoiceML\Model\CreateConversationsV1CredentialRequest;
use VoiceML\Model\UpdateConversationsV1CredentialRequest;

/** `/v1/Credentials` — Twilio Conversations v1 push Credentials. */
final class ConversationsV1CredentialsResource extends Resource
{

    /** @param array<string,mixed>|CreateConversationsV1CredentialRequest $body */
    public function create(array|CreateConversationsV1CredentialRequest $body): ConversationsV1Credential
    {
        $form = $body instanceof CreateConversationsV1CredentialRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Credentials'), null, $form);
        return ConversationsV1Credential::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1CredentialList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Credentials'), $query);
        return ConversationsV1CredentialList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1Credential
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Credentials', $sid));
        return ConversationsV1Credential::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateConversationsV1CredentialRequest $body */
    public function update(string $sid, array|UpdateConversationsV1CredentialRequest $body = []): ConversationsV1Credential
    {
        $form = $body instanceof UpdateConversationsV1CredentialRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Credentials', $sid), null, $form);
        return ConversationsV1Credential::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->v1Path('Credentials', $sid));
    }
}
