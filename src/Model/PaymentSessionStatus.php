<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `Status` field on Pay-session updates: `complete` captures the
 * collected fields, `cancel` aborts the session.
 */
enum PaymentSessionStatus: string
{
    case Complete = 'complete';
    case Cancel = 'cancel';
}
