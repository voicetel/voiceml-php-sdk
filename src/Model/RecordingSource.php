<?php

declare(strict_types=1);

namespace VoiceML\Model;

enum RecordingSource: string
{
    case OutboundAPI = 'OutboundAPI';
    case RecordVerb = 'RecordVerb';
    case DialVerb = 'DialVerb';
    case Conference = 'Conference';
    case Trunking = 'Trunking';
    case StartCallRecordingAPI = 'StartCallRecordingAPI';
    case StartConferenceRecordingAPI = 'StartConferenceRecordingAPI';
}
