<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Assistants/{id}/Feedbacks` response. */
final class AssistantsV1FeedbackList implements Model
{
    /** @param list<AssistantsV1Feedback> $feedbacks */
    public function __construct(
        public readonly array $feedbacks,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['feedbacks'] ?? []) as $row) {
            if (is_array($row)) {
                $items[] = AssistantsV1Feedback::fromArray($row);
            }
        }
        return new self(
            feedbacks: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
