<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Credentials`. */
final class CreateConversationsV1CredentialRequest
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $friendlyName = null,
        public readonly ?string $certificate = null,
        public readonly ?string $privateKey = null,
        public readonly ?bool $sandbox = null,
        public readonly ?string $apiKey = null,
        public readonly ?string $secret = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['Type' => $this->type];
        if ($this->friendlyName !== null) $out['FriendlyName'] = $this->friendlyName;
        if ($this->certificate !== null) $out['Certificate'] = $this->certificate;
        if ($this->privateKey !== null) $out['PrivateKey'] = $this->privateKey;
        if ($this->sandbox !== null) $out['Sandbox'] = $this->sandbox;
        if ($this->apiKey !== null) $out['ApiKey'] = $this->apiKey;
        if ($this->secret !== null) $out['Secret'] = $this->secret;
        return $out;
    }
}
