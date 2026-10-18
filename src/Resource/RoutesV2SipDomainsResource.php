<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\RoutesV2SipDomain;

/**
 * Operations on /v2/SipDomains/{SipDomain}. Keyed by SIP domain name; the
 * account is resolved from HTTP Basic auth, so the /v2/ namespace bypasses
 * the /2010-04-01/Accounts/{Sid}/ prefix.
 */
final class RoutesV2SipDomainsResource extends Resource
{

    public function fetch(string $domainName): RoutesV2SipDomain
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v2Path('SipDomains', $domainName));
        return RoutesV2SipDomain::fromArray($raw);
    }

    public function update(string $domainName, ?string $voiceRegion = null, ?string $friendlyName = null): RoutesV2SipDomain
    {
        $body = [];
        if ($voiceRegion !== null) $body['VoiceRegion'] = $voiceRegion;
        if ($friendlyName !== null) $body['FriendlyName'] = $friendlyName;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v2Path('SipDomains', $domainName), null, $body);
        return RoutesV2SipDomain::fromArray($raw);
    }
}
