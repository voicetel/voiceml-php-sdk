<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /v1/Assistants/{id}` response — the Assistant plus its attached Tools
 * and Knowledge arrays inlined.
 */
final class AssistantsV1AssistantWithToolsAndKnowledge implements Model
{
    /**
     * @param array<string,mixed>|null $customerAi
     * @param list<AssistantsV1Tool> $tools
     * @param list<AssistantsV1Knowledge> $knowledge
     */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $id,
        public readonly ?string $name,
        public readonly ?string $owner,
        public readonly ?string $model,
        public readonly ?string $personalityPrompt,
        public readonly ?array $customerAi,
        public readonly array $tools,
        public readonly array $knowledge,
        public readonly ?string $url = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $tools = [];
        foreach ((array) ($data['tools'] ?? []) as $row) {
            if (is_array($row)) {
                $tools[] = AssistantsV1Tool::fromArray($row);
            }
        }
        $know = [];
        foreach ((array) ($data['knowledge'] ?? []) as $row) {
            if (is_array($row)) {
                $know[] = AssistantsV1Knowledge::fromArray($row);
            }
        }
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            id: isset($data['id']) ? (string) $data['id'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            owner: isset($data['owner']) ? (string) $data['owner'] : null,
            model: isset($data['model']) ? (string) $data['model'] : null,
            personalityPrompt: isset($data['personality_prompt']) ? (string) $data['personality_prompt'] : null,
            customerAi: isset($data['customer_ai']) && is_array($data['customer_ai']) ? $data['customer_ai'] : null,
            tools: $tools,
            knowledge: $know,
            url: isset($data['url']) ? (string) $data['url'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
        );
    }
}
