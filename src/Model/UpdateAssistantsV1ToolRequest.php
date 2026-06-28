<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `PUT /v1/Tools/{id}`. JSON wire format. All fields optional. */
final class UpdateAssistantsV1ToolRequest
{
    /** @param array<string,mixed>|null $meta */
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $type = null,
        public readonly ?bool $enabled = null,
        public readonly ?string $description = null,
        public readonly ?array $meta = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->name !== null) {
            $out['name'] = $this->name;
        }
        if ($this->type !== null) {
            $out['type'] = $this->type;
        }
        if ($this->enabled !== null) {
            $out['enabled'] = $this->enabled;
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
