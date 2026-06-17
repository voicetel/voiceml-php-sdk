<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->sip` — top-level SIP Trunking holder.
 */
final class SipResource
{
    public readonly SipDomainsResource $domains;
    public readonly SipCredentialListsResource $credentialLists;
    public readonly SipIpAccessControlListsResource $ipAccessControlLists;

    public function __construct(Transport $transport)
    {
        $this->domains = new SipDomainsResource($transport);
        $this->credentialLists = new SipCredentialListsResource($transport);
        $this->ipAccessControlLists = new SipIpAccessControlListsResource($transport);
    }
}
