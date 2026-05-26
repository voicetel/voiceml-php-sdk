<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum ConferenceStatus: string
{
    case Init = 'init';
    case InProgress = 'in-progress';
    case Completed = 'completed';
}
