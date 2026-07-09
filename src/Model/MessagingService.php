<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * A Messaging Service — Twilio `MG…` resource (messaging.twilio.com/v1).
 *
 * Shares the `/v1/Services` path shape with a Conversation Service (`IS…`); the
 * two are disambiguated on the wire by host (`messaging.voicetel.com` vs
 * `conversations.voicetel.com`). The feature-toggle fields (`stickySender`,
 * `mmsConverter`, …) are accept-and-echo on VoiceML; the service's operative
 * role is gating scheduled sends.
 */
final class MessagingService implements Model
{
    public function __construct(
        public readonly ?string $sid,
        public readonly ?string $accountSid,
        public readonly ?string $friendlyName = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $inboundRequestUrl = null,
        public readonly ?string $inboundMethod = null,
        public readonly ?string $fallbackUrl = null,
        public readonly ?string $fallbackMethod = null,
        public readonly ?string $statusCallback = null,
        public readonly ?bool $stickySender = null,
        public readonly ?bool $mmsConverter = null,
        public readonly ?bool $smartEncoding = null,
        public readonly ?string $scanMessageContent = null,
        public readonly ?bool $fallbackToLongCode = null,
        public readonly ?bool $areaCodeGeomatch = null,
        public readonly ?bool $synchronousValidation = null,
        public readonly ?int $validityPeriod = null,
        public readonly ?string $url = null,
        public readonly ?string $usecase = null,
        public readonly ?bool $useInboundWebhookOnNumber = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            inboundRequestUrl: isset($data['inbound_request_url']) ? (string) $data['inbound_request_url'] : null,
            inboundMethod: isset($data['inbound_method']) ? (string) $data['inbound_method'] : null,
            fallbackUrl: isset($data['fallback_url']) ? (string) $data['fallback_url'] : null,
            fallbackMethod: isset($data['fallback_method']) ? (string) $data['fallback_method'] : null,
            statusCallback: isset($data['status_callback']) ? (string) $data['status_callback'] : null,
            stickySender: isset($data['sticky_sender']) ? (bool) $data['sticky_sender'] : null,
            mmsConverter: isset($data['mms_converter']) ? (bool) $data['mms_converter'] : null,
            smartEncoding: isset($data['smart_encoding']) ? (bool) $data['smart_encoding'] : null,
            scanMessageContent: isset($data['scan_message_content']) ? (string) $data['scan_message_content'] : null,
            fallbackToLongCode: isset($data['fallback_to_long_code']) ? (bool) $data['fallback_to_long_code'] : null,
            areaCodeGeomatch: isset($data['area_code_geomatch']) ? (bool) $data['area_code_geomatch'] : null,
            synchronousValidation: isset($data['synchronous_validation']) ? (bool) $data['synchronous_validation'] : null,
            validityPeriod: isset($data['validity_period']) ? (int) $data['validity_period'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            usecase: isset($data['usecase']) ? (string) $data['usecase'] : null,
            useInboundWebhookOnNumber: isset($data['use_inbound_webhook_on_number'])
                ? (bool) $data['use_inbound_webhook_on_number']
                : null,
        );
    }
}
