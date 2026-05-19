<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum CallDirection: string
{
    case Inbound = 'inbound';
    case OutboundApi = 'outbound-api';
    case OutboundDial = 'outbound-dial';
}
