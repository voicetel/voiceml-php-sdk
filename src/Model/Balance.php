<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Account balance (`GET /2010-04-01/Accounts/{AccountSid}/Balance.json`).
 */
final class Balance implements Model
{
    public function __construct(
        public readonly ?string $accountSid = null,
        public readonly ?string $balance = null,
        public readonly ?string $currency = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            balance: isset($data['balance']) ? (string) $data['balance'] : null,
            currency: isset($data['currency']) ? (string) $data['currency'] : null,
        );
    }
}
