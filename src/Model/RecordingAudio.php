<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Result of fetching `GET /Recordings/{sid}.wav`.
 *
 * `content` is the WAV bytes (after following any S3 redirect). `contentType` is whatever
 * the server (or S3) declared — typically `audio/wav` but pass through what we got rather
 * than assuming.
 */
final class RecordingAudio implements Model
{
    public function __construct(
        public readonly string $sid,
        public readonly string $content,
        public readonly string $contentType,
        public readonly bool $viaRedirect,
    ) {
    }
}
