<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Narrows the `Input` field on a `<Pay>` session. DTMF is the only supported value today.
 */
enum PaymentInput: string
{
    case Dtmf = 'dtmf';
}
