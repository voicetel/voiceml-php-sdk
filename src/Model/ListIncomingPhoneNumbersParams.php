<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Query params for `GET /IncomingPhoneNumbers` and the typed
 * `/IncomingPhoneNumbers/{Local,Mobile,TollFree}` list endpoints.
 *
 * Carries PhoneNumber, FriendlyName, Beta and Origin filters plus standard pagination.
 */
final class ListIncomingPhoneNumbersParams
{
    public function __construct(
        public readonly ?string $phoneNumber = null,
        public readonly ?string $friendlyName = null,
        public readonly ?bool $beta = null,
        public readonly ?string $origin = null,
        public readonly ?int $page = null,
        public readonly ?int $pageSize = null,
        public readonly ?string $pageToken = null,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toQuery(): array
    {
        return [
            'PhoneNumber' => $this->phoneNumber,
            'FriendlyName' => $this->friendlyName,
            'Beta' => $this->beta,
            'Origin' => $this->origin,
            'Page' => $this->page,
            'PageSize' => $this->pageSize,
            'PageToken' => $this->pageToken,
        ];
    }
}
