<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ExportsList;

/**
 * `/Exports` — downloadable account data bundles. `list()` enumerates the
 * available days; `download()` fetches a day's bundle bytes, following the
 * 302 redirect with the documented redirect-safety rules.
 */
final class ExportsResource extends Resource
{
    public function list(): ExportsList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Exports'));
        return ExportsList::fromArray($raw);
    }

    /**
     * Download one day's export bundle (`GET /Exports/{Date}`, no `.json`).
     *
     * @param string $date Day to download, `YYYY-MM-DD`.
     *
     * @return array{status:int, body:string, headers:array<string,array<int,string>>}
     */
    public function download(string $date): array
    {
        return $this->transport->fetchBytes($this->pathRaw('Exports', $date), '*/*');
    }
}
