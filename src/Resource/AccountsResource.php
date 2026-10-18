<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Account;
use VoiceML\Model\AccountWithKey;
use VoiceML\Model\Balance;
use VoiceML\Model\UpdateAccountRequest;

/**
 * `/2010-04-01/Accounts/{Sid}` — the Account resource itself, and the
 * account balance (`/Balance`).
 */
final class AccountsResource extends Resource
{
    public function fetch(?string $sid = null): Account
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->accountPath($sid));
        return Account::fromArray($raw);
    }

    /**
     * Update the account's settings (partial). A 409 response surfaces as
     * {@see \VoiceML\Exception\ConflictException} — the account changed since
     * it was read; re-fetch, reconcile, and retry.
     */
    public function update(?string $sid, UpdateAccountRequest $body): Account
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->accountPath($sid), null, $body->toForm());
        return Account::fromArray($raw);
    }

    public function balance(): Balance
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Balance'));
        return Balance::fromArray($raw);
    }

    /**
     * Rotate the account's API key (`POST /RotateKey`). The response carries
     * the new key — store it; the previous key stops working immediately.
     * Handle the 409 (optimistic-lock) like {@see update()}.
     */
    public function rotateKey(): AccountWithKey
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('RotateKey'));
        return AccountWithKey::fromArray($raw);
    }

    /**
     * `accounts.fetch`/`accounts.update` take the account sid as the first
     * path segment (not under `/Accounts/{AccountSid}/…`).
     */
    private function accountPath(?string $sid): string
    {
        return '/2010-04-01/Accounts/' . self::encodeSegment($sid ?? $this->transport->accountSid()) . '.json';
    }
}
