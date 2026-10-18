<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A CallEvent entry from `GET /Calls/{sid}/Events` — a raw request/response
 * pair from the call's control exchange. Unknown fields are ignored.
 */
final class CallEvent implements Model
{
    /**
     * @param array<string,mixed>|null $request
     * @param array<string,mixed>|null $response
     */
    public function __construct(
        public readonly ?array $request = null,
        public readonly ?array $response = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,mixed>|null $request */
        $request = isset($data['request']) && is_array($data['request']) ? $data['request'] : null;
        /** @var array<string,mixed>|null $response */
        $response = isset($data['response']) && is_array($data['response']) ? $data['response'] : null;

        return new self(request: $request, response: $response);
    }
}
