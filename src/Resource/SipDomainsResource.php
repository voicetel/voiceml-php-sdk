<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\SipCredentialListMappingList;
use VoiceML\Model\SipDomain;
use VoiceML\Model\SipDomainList;
use VoiceML\Model\SipDomainMapping;
use VoiceML\Model\SipIpAccessControlListMappingList;

/**
 * `/SIP/Domains` + the four mapping endpoints (historical + Auth/Calls + Auth/Registrations).
 */
final class SipDomainsResource extends Resource
{
    /** @param array<string,mixed> $body */
    public function create(array $body): SipDomain
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'Domains'), null, $body);
        return SipDomain::fromArray($raw);
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): SipDomainList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains'), $query);
        return SipDomainList::fromArray($raw);
    }

    public function fetch(string $domainSid): SipDomain
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid));
        return SipDomain::fromArray($raw);
    }

    /** @param array<string,mixed> $body */
    public function update(string $domainSid, array $body): SipDomain
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'Domains', $domainSid), null, $body);
        return SipDomain::fromArray($raw);
    }

    public function delete(string $domainSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid));
    }

    // --- Historical CredentialList mappings ----------------------------------

    /** @param array<string,mixed> $query */
    public function listCredentialListMappings(string $domainSid, array $query = []): SipCredentialListMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'CredentialListMappings'), $query);
        return SipCredentialListMappingList::fromArray($raw);
    }

    public function createCredentialListMapping(string $domainSid, string $credentialListSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'Domains', $domainSid, 'CredentialListMappings'),
            null, ['CredentialListSid' => $credentialListSid]);
        return SipDomainMapping::fromArray($raw);
    }

    public function fetchCredentialListMapping(string $domainSid, string $mappingSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'CredentialListMappings', $mappingSid));
        return SipDomainMapping::fromArray($raw);
    }

    public function deleteCredentialListMapping(string $domainSid, string $mappingSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid, 'CredentialListMappings', $mappingSid));
    }

    // --- Historical IpAccessControlList mappings -----------------------------

    /** @param array<string,mixed> $query */
    public function listIpAccessControlListMappings(string $domainSid, array $query = []): SipIpAccessControlListMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'IpAccessControlListMappings'), $query);
        return SipIpAccessControlListMappingList::fromArray($raw);
    }

    public function createIpAccessControlListMapping(string $domainSid, string $ipAccessControlListSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'Domains', $domainSid, 'IpAccessControlListMappings'),
            null, ['IpAccessControlListSid' => $ipAccessControlListSid]);
        return SipDomainMapping::fromArray($raw);
    }

    public function fetchIpAccessControlListMapping(string $domainSid, string $mappingSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'IpAccessControlListMappings', $mappingSid));
        return SipDomainMapping::fromArray($raw);
    }

    public function deleteIpAccessControlListMapping(string $domainSid, string $mappingSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid, 'IpAccessControlListMappings', $mappingSid));
    }

    // --- Auth/Calls/CredentialListMappings -----------------------------------

    /** @param array<string,mixed> $query */
    public function listAuthCallsCredentialListMappings(string $domainSid, array $query = []): SipCredentialListMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'CredentialListMappings'), $query);
        return SipCredentialListMappingList::fromArray($raw);
    }

    public function createAuthCallsCredentialListMapping(string $domainSid, string $credentialListSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'CredentialListMappings'),
            null, ['CredentialListSid' => $credentialListSid]);
        return SipDomainMapping::fromArray($raw);
    }

    public function fetchAuthCallsCredentialListMapping(string $domainSid, string $mappingSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'CredentialListMappings', $mappingSid));
        return SipDomainMapping::fromArray($raw);
    }

    public function deleteAuthCallsCredentialListMapping(string $domainSid, string $mappingSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'CredentialListMappings', $mappingSid));
    }

    // --- Auth/Calls/IpAccessControlListMappings ------------------------------

    /** @param array<string,mixed> $query */
    public function listAuthCallsIpAccessControlListMappings(string $domainSid, array $query = []): SipIpAccessControlListMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'IpAccessControlListMappings'), $query);
        return SipIpAccessControlListMappingList::fromArray($raw);
    }

    public function createAuthCallsIpAccessControlListMapping(string $domainSid, string $ipAccessControlListSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'IpAccessControlListMappings'),
            null, ['IpAccessControlListSid' => $ipAccessControlListSid]);
        return SipDomainMapping::fromArray($raw);
    }

    public function fetchAuthCallsIpAccessControlListMapping(string $domainSid, string $mappingSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'IpAccessControlListMappings', $mappingSid));
        return SipDomainMapping::fromArray($raw);
    }

    public function deleteAuthCallsIpAccessControlListMapping(string $domainSid, string $mappingSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Calls', 'IpAccessControlListMappings', $mappingSid));
    }

    // --- Auth/Registrations/CredentialListMappings (no IP-ACL counterpart) --

    /** @param array<string,mixed> $query */
    public function listAuthRegistrationsCredentialListMappings(string $domainSid, array $query = []): SipCredentialListMappingList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Registrations', 'CredentialListMappings'), $query);
        return SipCredentialListMappingList::fromArray($raw);
    }

    public function createAuthRegistrationsCredentialListMapping(string $domainSid, string $credentialListSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Registrations', 'CredentialListMappings'),
            null, ['CredentialListSid' => $credentialListSid]);
        return SipDomainMapping::fromArray($raw);
    }

    public function fetchAuthRegistrationsCredentialListMapping(string $domainSid, string $mappingSid): SipDomainMapping
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Registrations', 'CredentialListMappings', $mappingSid));
        return SipDomainMapping::fromArray($raw);
    }

    public function deleteAuthRegistrationsCredentialListMapping(string $domainSid, string $mappingSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'Domains', $domainSid, 'Auth', 'Registrations', 'CredentialListMappings', $mappingSid));
    }
}
