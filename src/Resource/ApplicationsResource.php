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
}
