<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use Closure;
use VoiceML\Model\Model;
use VoiceML\Model\PricingCountriesList;
use VoiceML\Transport;

/**
 * A pricing `.../Countries` sub-resource: a `list()` returning the shared
 * {@see PricingCountriesList} envelope plus a per-country `fetch()` returning
 * the product-specific country body.
 *
 * The product-specific body model is supplied as a factory so this one class
 * serves Voice / Messaging / PhoneNumbers / Trunking across both API versions.
 */
final class PricingCountriesResource
{
    /** @param Closure(array<string,mixed>):Model $factory */
    public function __construct(
        private readonly Transport $transport,
        private readonly string $basePath,
        private readonly Closure $factory,
    ) {
    }

    /**
     * @param array<string,mixed> $query Optional `PageSize`.
     */
    public function list(array $query = []): PricingCountriesList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->basePath, $query);
        return PricingCountriesList::fromArray($raw);
    }

    public function fetch(string $isoCountry): Model
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request('GET', $this->basePath . '/' . rawurlencode($isoCountry));
        return ($this->factory)($raw);
    }
}
