<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum ParticipantStatus: string
{
    case Queued = 'queued';
    case Connecting = 'connecting';
    case Ringing = 'ringing';
    case Connected = 'connected';
    case OnHold = 'on-hold';
    case Complete = 'complete';
    case Failed = 'failed';
    case Completed = 'completed';
}
