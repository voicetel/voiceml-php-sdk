<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `BankAccountType` field on a `<Pay>` session.
 */
enum PaymentBankAccountType: string
{
    case ConsumerChecking = 'consumer-checking';
    case ConsumerSavings = 'consumer-savings';
    case CommercialChecking = 'commercial-checking';
}
