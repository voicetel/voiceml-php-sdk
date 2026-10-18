<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\Application;
use VoiceML\Model\ApplicationList;
use VoiceML\Model\CreateApplicationRequest;
use VoiceML\Model\ListApplicationsParams;
use VoiceML\Model\UpdateApplicationRequest;

/**
 * `/Applications` resource.
 */
final class ApplicationsResource extends Resource
{
    public function create(CreateApplicationRequest $body): Application
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Applications'), null, $body->toForm());
        return Application::fromArray($raw);
    }

    public function list(?ListApplicationsParams $params = null): ApplicationList
    {
        $query = ($params ?? new ListApplicationsParams())->toQuery();
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Applications'), $query);
        return ApplicationList::fromArray($raw);
    }

    public function get(string $applicationSid): Application
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->path('Applications', $applicationSid));
        return Application::fromArray($raw);
    }

    public function update(string $applicationSid, UpdateApplicationRequest $body): Application
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('POST', $this->path('Applications', $applicationSid), null, $body->toForm());
        return Application::fromArray($raw);
    }

    public function delete(string $applicationSid): void
    {
        $this->transport->request('DELETE', $this->path('Applications', $applicationSid));
    }

    /**
     * Fetch the page a previous result's `next_page_uri` points at, following
     * the link verbatim. Returns null on the last page.
     */
    public function nextPage(ApplicationList $page): ?ApplicationList
    {
        if ($page->nextPageUri === null || $page->nextPageUri === '') {
            return null;
        }
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->requestPageUri($page->nextPageUri);
        return ApplicationList::fromArray($raw);
    }

    /**
     * Generator that lazily walks all pages of `/Applications`, yielding one
     * Application at a time. Follows `next_page_uri` verbatim; stopping the
     * generator early makes no further requests.
     *
     * @return \Generator<int, Application>
     */
    public function iterate(?string $friendlyName = null, ?int $pageSize = null): \Generator
    {
        $page = $this->list(new ListApplicationsParams(
            friendlyName: $friendlyName,
            pageSize: $pageSize,
        ));
        while (true) {
            foreach ($page->applications as $application) {
                yield $application;
            }
            if ($page->nextPageUri === null || $page->nextPageUri === '' || $page->applications === []) {
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
