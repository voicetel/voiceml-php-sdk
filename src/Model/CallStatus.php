<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum CallStatus: string
{
    case Queued = 'queued';
    case Ringing = 'ringing';
    case InProgress = 'in-progress';
    case Completed = 'completed';
    case Busy = 'busy';
    case NoAnswer = 'no-answer';
    case Canceled = 'canceled';
    case Failed = 'failed';
}
