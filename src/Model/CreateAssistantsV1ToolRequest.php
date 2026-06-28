<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Tools`. JSON wire format. */
final class CreateAssistantsV1ToolRequest
{
    /** @param array<string,mixed>|null $meta */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly bool $enabled,
        public readonly ?string $assistantId = null,
        public readonly ?string $description = null,
        public readonly ?array $meta = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [
            'name' => $this->name,
            'type' => $this->type,
            'enabled' => $this->enabled,
        ];
        if ($this->assistantId !== null) {
            $out['assistant_id'] = $this->assistantId;
        }
        if ($this->description !== null) {
            $out['description'] = $this->description;
        }
        if ($this->meta !== null) {
            $out['meta'] = $this->meta;
        }
        return $out;
    }
}
