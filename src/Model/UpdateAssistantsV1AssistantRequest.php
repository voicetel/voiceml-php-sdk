<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `PUT /v1/Assistants/{id}`. JSON wire format. All fields optional. */
final class UpdateAssistantsV1AssistantRequest
{
    /**
     * @param array<string,mixed>|null $customerAi
     * @param array<string,mixed>|null $segmentCredential
     */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $owner = null,
        public readonly ?string $personalityPrompt = null,
        public readonly ?string $model = null,
        public readonly ?array $customerAi = null,
        public readonly ?array $segmentCredential = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->name !== null) {
            $out['name'] = $this->name;
        }
        if ($this->owner !== null) {
            $out['owner'] = $this->owner;
        }
        if ($this->personalityPrompt !== null) {
            $out['personality_prompt'] = $this->personalityPrompt;
        }
        if ($this->model !== null) {
            $out['model'] = $this->model;
        }
        if ($this->customerAi !== null) {
            $out['customer_ai'] = $this->customerAi;
        }
        if ($this->segmentCredential !== null) {
            $out['segment_credential'] = $this->segmentCredential;
        }
        return $out;
    }
}
