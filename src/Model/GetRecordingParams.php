<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Optional query params for `GET /Recordings/{sid}`.
 */
final class GetRecordingParams
{
    public function __construct(
        public readonly ?bool $includeSoftDeleted = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return [
            'IncludeSoftDeleted' => $this->includeSoftDeleted,
        ];
    }
}
