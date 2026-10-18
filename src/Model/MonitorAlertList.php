<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * `GET /v1/Alerts` list envelope (standard `/v1/` `meta` sub-object).
 */
final class MonitorAlertList implements Model
{
    /**
     * @param list<MonitorAlert> $alerts
     */
    public function __construct(
        public readonly array $alerts = [],
        public readonly ?VoiceV1Meta $meta = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        /** @var list<MonitorAlert> $items */
        $items = [];
        if (isset($data['alerts']) && is_array($data['alerts'])) {
            foreach ($data['alerts'] as $v) {
                $items[] = MonitorAlert::fromArray((array) $v);
            }
        }

        return new self(
            alerts: $items,
            meta: isset($data['meta']) && is_array($data['meta']) ? VoiceV1Meta::fromArray($data['meta']) : null,
        );
    }
}
