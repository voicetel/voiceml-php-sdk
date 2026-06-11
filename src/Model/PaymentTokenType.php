<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `TokenType` field on a `<Pay>` session.
 */
enum PaymentTokenType: string
{
    case OneTime = 'one-time';
    case Reusable = 'reusable';
    case PaymentMethod = 'payment-method';
}
