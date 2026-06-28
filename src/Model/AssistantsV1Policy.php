<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Policy (`aia_plcy_…`) — auth/permission rule for a Tool or Knowledge. */
final class AssistantsV1Policy implements Model
{
    /** @param array<string,mixed>|null $policyDetails */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly ?string $userSid,
        public readonly ?string $type,
        public readonly ?array $policyDetails = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            description: isset($data['description']) ? (string) $data['description'] : null,
            userSid: isset($data['user_sid']) ? (string) $data['user_sid'] : null,
            type: isset($data['type']) ? (string) $data['type'] : null,
            policyDetails: isset($data['policy_details']) && is_array($data['policy_details']) ? $data['policy_details'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
