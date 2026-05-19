<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{sid}`.
 *
 * Three flows on the same endpoint (mirrors Twilio):
 *   * `status="completed"|"canceled"` — terminate the call. Wins over any TwiML source.
 *   * `twiml=<inline>` — execute inline TwiML on the live call (wins over `url`).
 *   * `url=…` — fetch new TwiML and execute it on the live call.
 *
 * StatusCallback fields apply independently — including on the terminate path.
 */
final class UpdateCallRequest extends FormRequest
{
    /**
     * @param list<string>|null $statusCallbackEvent
     */
    public function __construct(
        public readonly ?string $status = null,
        public readonly ?string $twiml = null,
        public readonly ?string $url = null,
        public readonly ?string $method = null,
        public readonly ?string $fallbackUrl = null,
        public readonly ?string $fallbackMethod = null,
        public readonly ?string $statusCallback = null,
        public readonly ?string $statusCallbackMethod = null,
        public readonly ?array $statusCallbackEvent = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'Status' => 'status',
            'Twiml' => 'twiml',
            'Url' => 'url',
            'Method' => 'method',
            'FallbackUrl' => 'fallbackUrl',
            'FallbackMethod' => 'fallbackMethod',
            'StatusCallback' => 'statusCallback',
            'StatusCallbackMethod' => 'statusCallbackMethod',
            'StatusCallbackEvent' => 'statusCallbackEvent',
        ];
    }
}
