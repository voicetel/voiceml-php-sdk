<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Assistants`. Wire format is JSON; field keys are snake_case. */
final class CreateAssistantsV1AssistantRequest
{
    /**
     * @param array<string,mixed>|null $customerAi
     * @param array<string,mixed>|null $segmentCredential
     */
    public function __construct(
        public readonly string $name,
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
        $out = ['name' => $this->name];
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
