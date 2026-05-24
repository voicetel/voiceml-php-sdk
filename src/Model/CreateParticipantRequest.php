<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Form body for `POST /Conferences/{sid}/Participants`. `from` and `to` are required.
 */
final class CreateParticipantRequest
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly ?string $label = null,
        public readonly ?bool $muted = null,
        public readonly ?bool $startConferenceOnEnter = null,
        public readonly ?bool $endConferenceOnExit = null,
        public readonly ?int $timeout = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?string $statusCallbackEvent = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toForm(): array
    {
        return [
            'From' => $this->from,
            'To' => $this->to,
            'Label' => $this->label,
            'Muted' => $this->muted,
            'StartConferenceOnEnter' => $this->startConferenceOnEnter,
            'EndConferenceOnExit' => $this->endConferenceOnExit,
            'Timeout' => $this->timeout,
            'StatusCallback' => $this->statusCallback,
            'StatusCallbackMethod' => $this->statusCallbackMethod,
            'StatusCallbackEvent' => $this->statusCallbackEvent,
        ];
    }
}
