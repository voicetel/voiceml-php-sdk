<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->voiceV1` — top-level holder for the Twilio Voice v1
 * (voice.twilio.com/v1) family: IpRecords, SourceIpMappings, ByocTrunks,
 * ConnectionPolicies (+ Targets), and account-wide DialingPermissions Settings.
 */
final class VoiceV1Resource
{
    public readonly VoiceV1IpRecordsResource $ipRecords;
    public readonly VoiceV1SourceIpMappingsResource $sourceIpMappings;
    public readonly VoiceV1ByocTrunksResource $byocTrunks;
    public readonly VoiceV1ConnectionPoliciesResource $connectionPolicies;
    public readonly VoiceV1SettingsResource $settings;

    public function __construct(Transport $transport)
    {
        $this->ipRecords = new VoiceV1IpRecordsResource($transport);
        $this->sourceIpMappings = new VoiceV1SourceIpMappingsResource($transport);
        $this->byocTrunks = new VoiceV1ByocTrunksResource($transport);
        $this->connectionPolicies = new VoiceV1ConnectionPoliciesResource($transport);
        $this->settings = new VoiceV1SettingsResource($transport);
    }
}
