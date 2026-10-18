<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\UpdateVoiceV1DialingPermissionsSettingsRequest;
use VoiceML\Model\VoiceV1DialingPermissionsSettings;

/** `/v1/Settings` — account-wide dialing-permissions inheritance flag. */
final class VoiceV1SettingsResource extends Resource
{

    public function fetch(): VoiceV1DialingPermissionsSettings
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->v1Path('Settings'));
        return VoiceV1DialingPermissionsSettings::fromArray($raw);
    }

    /** @param array<string,mixed>|UpdateVoiceV1DialingPermissionsSettingsRequest $body */
    public function update(array|UpdateVoiceV1DialingPermissionsSettingsRequest $body = []): VoiceV1DialingPermissionsSettings
    {
        $form = $body instanceof UpdateVoiceV1DialingPermissionsSettingsRequest ? $body->toArray() : $body;
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->v1Path('Settings'), null, $form);
        return VoiceV1DialingPermissionsSettings::fromArray($raw);
    }
}
