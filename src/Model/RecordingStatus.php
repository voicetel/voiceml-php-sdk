<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum RecordingStatus: string
{
    case InProgress = 'in-progress';
    case Paused = 'paused';
    case Stopped = 'stopped';
    case Processing = 'processing';
    case Completed = 'completed';
    case Absent = 'absent';
    case Deleted = 'deleted';
}
