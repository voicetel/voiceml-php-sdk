<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** An Assistants v1 Assistant (`aia_asst_…`). Mirrors Twilio AI-Assistants. */
final class AssistantsV1Assistant implements Model
{
    /** @param array<string,mixed>|null $customerAi */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $owner,
        public readonly ?string $model,
        public readonly ?string $personalityPrompt,
        public readonly ?array $customerAi = null,
        public readonly ?string $url = null,
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
            owner: isset($data['owner']) ? (string) $data['owner'] : null,
            model: isset($data['model']) ? (string) $data['model'] : null,
            personalityPrompt: isset($data['personality_prompt']) ? (string) $data['personality_prompt'] : null,
            customerAi: isset($data['customer_ai']) && is_array($data['customer_ai']) ? $data['customer_ai'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
