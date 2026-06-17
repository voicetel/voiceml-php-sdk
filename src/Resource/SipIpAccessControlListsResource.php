<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\SipIpAccessControlList;
use VoiceML\Model\SipIpAccessControlListList;
use VoiceML\Model\SipIpAddress;
use VoiceML\Model\SipIpAddressList;

/** `/SIP/IpAccessControlLists` + per-list /IpAddresses. */
final class SipIpAccessControlListsResource extends Resource
{
    /** @param array<string,mixed> $query */
    public function list(array $query = []): SipIpAccessControlListList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'IpAccessControlLists'), $query);
        return SipIpAccessControlListList::fromArray($raw);
    }

    public function create(string $friendlyName): SipIpAccessControlList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'IpAccessControlLists'),
            null, ['FriendlyName' => $friendlyName]);
        return SipIpAccessControlList::fromArray($raw);
    }

    public function fetch(string $aclSid): SipIpAccessControlList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('SIP', 'IpAccessControlLists', $aclSid));
        return SipIpAccessControlList::fromArray($raw);
    }

    public function update(string $aclSid, ?string $friendlyName = null): SipIpAccessControlList
    {
        $body = $friendlyName === null ? [] : ['FriendlyName' => $friendlyName];
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('SIP', 'IpAccessControlLists', $aclSid), null, $body);
        return SipIpAccessControlList::fromArray($raw);
    }

    public function delete(string $aclSid): void
    {
        $this->transport->request('DELETE', $this->path('SIP', 'IpAccessControlLists', $aclSid));
    }

    // --- /IpAddresses ---

    /** @param array<string,mixed> $query */
    public function listIpAddresses(string $aclSid, array $query = []): SipIpAddressList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET',
            $this->path('SIP', 'IpAccessControlLists', $aclSid, 'IpAddresses'), $query);
        return SipIpAddressList::fromArray($raw);
    }

    public function createIpAddress(string $aclSid, string $friendlyName, string $ipAddress, ?int $cidrPrefixLength = null): SipIpAddress
    {
        $body = ['FriendlyName' => $friendlyName, 'IpAddress' => $ipAddress];
        if ($cidrPrefixLength !== null) $body['CidrPrefixLength'] = (string) $cidrPrefixLength;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'IpAccessControlLists', $aclSid, 'IpAddresses'), null, $body);
        return SipIpAddress::fromArray($raw);
    }

    public function fetchIpAddress(string $aclSid, string $ipAddressSid): SipIpAddress
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET',
            $this->path('SIP', 'IpAccessControlLists', $aclSid, 'IpAddresses', $ipAddressSid));
        return SipIpAddress::fromArray($raw);
    }

    /** @param array<string,mixed> $body */
    public function updateIpAddress(string $aclSid, string $ipAddressSid, array $body): SipIpAddress
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST',
            $this->path('SIP', 'IpAccessControlLists', $aclSid, 'IpAddresses', $ipAddressSid),
            null, $body);
        return SipIpAddress::fromArray($raw);
    }

    public function deleteIpAddress(string $aclSid, string $ipAddressSid): void
    {
        $this->transport->request('DELETE',
            $this->path('SIP', 'IpAccessControlLists', $aclSid, 'IpAddresses', $ipAddressSid));
    }
}
