<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /Calls/{CallSid}/Payments`. Sent form-encoded.
 *
 * Every attribute the `<Pay>` TwiML verb accepts has a counterpart here.
 * `idempotencyKey` is accepted and persisted for diagnostic visibility, but replay
 * dedup is not enforced today.
 */
final class StartPaymentRequest extends FormRequest
{
    public function __construct(
        public readonly ?string $idempotencyKey = null,
        public readonly ?string $statusCallback = null,
        public readonly ?PaymentBankAccountType $bankAccountType = null,
        public readonly ?string $chargeAmount = null,
        public readonly ?string $currency = null,
        public readonly ?string $description = null,
        public readonly ?PaymentInput $input = null,
        public readonly ?int $minPostalCodeLength = null,
        public readonly ?string $parameter = null,
        public readonly ?string $paymentConnector = null,
        public readonly ?PaymentMethod $paymentMethod = null,
        public readonly ?bool $postalCode = null,
        public readonly ?bool $securityCode = null,
        public readonly ?int $timeout = null,
        public readonly ?PaymentTokenType $tokenType = null,
        public readonly ?string $validCardTypes = null,
        public readonly ?string $requireMatchingInputs = null,
        public readonly ?bool $confirmation = null,
    ) {
    }

    protected static function fieldMap(): array
    {
        return [
            'IdempotencyKey' => 'idempotencyKey',
            'StatusCallback' => 'statusCallback',
            'BankAccountType' => 'bankAccountType',
            'ChargeAmount' => 'chargeAmount',
            'Currency' => 'currency',
            'Description' => 'description',
            'Input' => 'input',
            'MinPostalCodeLength' => 'minPostalCodeLength',
            'Parameter' => 'parameter',
            'PaymentConnector' => 'paymentConnector',
            'PaymentMethod' => 'paymentMethod',
            'PostalCode' => 'postalCode',
            'SecurityCode' => 'securityCode',
            'Timeout' => 'timeout',
            'TokenType' => 'tokenType',
            'ValidCardTypes' => 'validCardTypes',
            'RequireMatchingInputs' => 'requireMatchingInputs',
            'Confirmation' => 'confirmation',
        ];
    }
}
