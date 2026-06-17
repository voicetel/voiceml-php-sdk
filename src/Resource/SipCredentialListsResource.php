<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\SipCredential;
use VoiceML\Model\SipCredentialList;
use VoiceML\Model\SipCredentialListList;
use VoiceML\Model\SipCredentialListPage;

/** `/SIP/CredentialLists` + per-list /Credentials. */
final class SipCredentialListsResource extends Resource
{
    /** @param array<string,mixed> $query */
    public function list(array $query = []): SipCredentialListList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'CredentialLists'), $query);
        return SipCredentialListList::fromArray($raw);
    }

    public function create(string $friendlyName): SipCredentialList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'CredentialLists'),
            null, ['FriendlyName' => $friendlyName]);
        return SipCredentialList::fromArray($raw);
    }

    public function fetch(string $credentialListSid): SipCredentialList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'CredentialLists', $credentialListSid));
        return SipCredentialList::fromArray($raw);
    }

    public function update(string $credentialListSid, ?string $friendlyName = null): SipCredentialList
    {
        $body = $friendlyName === null ? [] : ['FriendlyName' => $friendlyName];
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'CredentialLists', $credentialListSid), null, $body);
        return SipCredentialList::fromArray($raw);
    }

    public function delete(string $credentialListSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'CredentialLists', $credentialListSid));
    }

    // --- /Credentials ---

    /** @param array<string,mixed> $query */
    public function listCredentials(string $credentialListSid, array $query = []): SipCredentialListPage
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET',
            $this->path('SIP', 'CredentialLists', $credentialListSid, 'Credentials'), $query);
        return SipCredentialListPage::fromArray($raw);
    }

    public function createCredential(string $credentialListSid, string $username, string $password): SipCredential
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'CredentialLists', $credentialListSid, 'Credentials'),
            null, ['Username' => $username, 'Password' => $password]);
        return SipCredential::fromArray($raw);
    }

    public function fetchCredential(string $credentialListSid, string $credentialSid): SipCredential
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET',
            $this->path('SIP', 'CredentialLists', $credentialListSid, 'Credentials', $credentialSid));
        return SipCredential::fromArray($raw);
    }

    public function updateCredential(string $credentialListSid, string $credentialSid, string $password): SipCredential
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'CredentialLists', $credentialListSid, 'Credentials', $credentialSid),
            null, ['Password' => $password]);
        return SipCredential::fromArray($raw);
    }

    public function deleteCredential(string $credentialListSid, string $credentialSid): void
    {
        $this->transport->request('DELETE',
            $this->path('SIP', 'CredentialLists', $credentialListSid, 'Credentials', $credentialSid));
    }
}
