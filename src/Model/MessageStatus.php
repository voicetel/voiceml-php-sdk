<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Twilio-compatible status enum surfaced on the `Message` resource.
 *
 * VoiceTel's SDK 2.2 gateway is fire-and-forget — the wire pins to either
 * `sent` or `failed` on every successful dispatch today. The other cases are
 * carried here for forward compatibility with the documented Twilio set so a
 * later gateway with delivery receipts can populate them without an SDK bump.
 */
enum MessageStatus: string
{
    case Queued = 'queued';
    case Sending = 'sending';
    case Sent = 'sent';
    case Failed = 'failed';
    case Delivered = 'delivered';
    case Undelivered = 'undelivered';
    case Receiving = 'receiving';
    case Received = 'received';
    case Accepted = 'accepted';
    case Scheduled = 'scheduled';
    case Read = 'read';
    case Canceled = 'canceled';
}
