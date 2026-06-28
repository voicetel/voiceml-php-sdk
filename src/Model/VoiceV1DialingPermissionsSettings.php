<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Account-wide dialing-permissions inheritance setting. */
final class VoiceV1DialingPermissionsSettings implements Model
{
    public function __construct(
        public readonly ?bool $dialingPermissionsInheritance = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            dialingPermissionsInheritance: isset($data['dialing_permissions_inheritance'])
                ? (bool) $data['dialing_permissions_inheritance']
                : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
