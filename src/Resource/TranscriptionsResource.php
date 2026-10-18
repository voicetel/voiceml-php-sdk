<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ListTranscriptionsParams;
use VoiceML\Model\Transcription;
use VoiceML\Model\TranscriptionList;

/**
 * Account-scoped `/Transcriptions` — completed transcription records.
 * (Live call transcriptions live on {@see CallsResource}; recording-scoped
 * ones on {@see RecordingsResource}.)
 */
final class TranscriptionsResource extends Resource
{
    public function list(?ListTranscriptionsParams $params = null): TranscriptionList
    {
        $query = ($params ?? new ListTranscriptionsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Transcriptions'), $query);
        return TranscriptionList::fromArray($raw);
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(TranscriptionList $page): ?TranscriptionList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return TranscriptionList::fromArray($raw);
    }

    public function fetch(string $sid): Transcription
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Transcriptions', $sid));
        return Transcription::fromArray($raw);
    }

    public function delete(string $sid): void
    {
        $this->transport->request('DELETE', $this->path('Transcriptions', $sid));
    }

    /**
     * Generator that lazily walks all pages of `/Transcriptions`.
     * Follows `next_page_uri` verbatim.
     *
     * @return \Generator<int, Transcription>
     */
    public function iterate(?ListTranscriptionsParams $params = null): \Generator
    {
        $page = $this->list($params);
        while (true) {
            foreach ($page->transcriptions as $transcription) {
                yield $transcription;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->transcriptions === []) {
                return;
            }
            $next = $this->nextPage($page);
            if ($next === null) {
                return;
            }
            $page = $next;
        }
    }
}
