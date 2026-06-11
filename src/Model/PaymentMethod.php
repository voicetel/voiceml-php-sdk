<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `PaymentMethod` field on a `<Pay>` session.
 */
enum PaymentMethod: string
{
    case CreditCard = 'credit-card';
    case AchDebit = 'ach-debit';
}
