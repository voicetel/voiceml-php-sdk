<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `Capture` field on Pay-session updates — tells the runtime which input
 * the user is about to type next.
 */
enum PaymentCapture: string
{
    case PaymentCardNumber = 'payment-card-number';
    case ExpirationDate = 'expiration-date';
    case SecurityCode = 'security-code';
    case PostalCode = 'postal-code';
    case BankRoutingNumber = 'bank-routing-number';
    case BankAccountNumber = 'bank-account-number';
    case PaymentCardNumberMatcher = 'payment-card-number-matcher';
    case ExpirationDateMatcher = 'expiration-date-matcher';
    case SecurityCodeMatcher = 'security-code-matcher';
    case PostalCodeMatcher = 'postal-code-matcher';
}
