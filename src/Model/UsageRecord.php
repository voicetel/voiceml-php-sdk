<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A usage record row (`/Usage/Records[/{Window}]`).
 */
final class UsageRecord implements Model
{
    /**
     * @param array<string,string>|null $subresourceUris
     */
    public function __construct(
        public readonly ?string $accountSid = null,
        public readonly ?string $apiVersion = null,
        public readonly ?string $asOf = null,
        public readonly ?string $category = null,
        public readonly ?string $count = null,
        public readonly ?string $countUnit = null,
        public readonly ?string $description = null,
        public readonly ?string $endDate = null,
        public readonly ?string $price = null,
        public readonly ?string $priceUnit = null,
        public readonly ?string $startDate = null,
        public readonly ?array $subresourceUris = null,
        public readonly ?string $uri = null,
        public readonly ?string $usage = null,
        public readonly ?string $usageUnit = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string,string>|null $subresourceUris */
        $subresourceUris = isset($data['subresource_uris']) && is_array($data['subresource_uris'])
            ? array_map(static fn ($v): string => (string) $v, $data['subresource_uris'])
            : null;

        $str = static fn (string $key): ?string => isset($data[$key]) ? (string) $data[$key] : null;

        return new self(
            accountSid: $str('account_sid'),
            apiVersion: $str('api_version'),
            asOf: $str('as_of'),
            category: $str('category'),
            count: $str('count'),
            countUnit: $str('count_unit'),
            description: $str('description'),
            endDate: $str('end_date'),
            price: $str('price'),
            priceUnit: $str('price_unit'),
            startDate: $str('start_date'),
            subresourceUris: $subresourceUris,
            uri: $str('uri'),
            usage: $str('usage'),
            usageUnit: $str('usage_unit'),
        );
    }
}
