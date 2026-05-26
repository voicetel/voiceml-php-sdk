<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum AnsweredBy: string
{
    case Human = 'human';
    case MachineStart = 'machine_start';
    case MachineEndBeep = 'machine_end_beep';
    case MachineEndSilence = 'machine_end_silence';
    case MachineEndOther = 'machine_end_other';
    case Fax = 'fax';
    case Unknown = 'unknown';
}
