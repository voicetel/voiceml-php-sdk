<?php

declare(strict_types=1);

namespace VoiceML\Model;

/**
 * Body for `POST /v1/Services` (messaging host). `friendlyName` is required;
 * every other field is an optional Messaging Service config toggle.
 */
final class CreateMessagingServiceRequest
{
    public function __construct(
        public readonly string $friendlyName,
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
        public readonly ?string $usecase = null,
        public readonly ?bool $useInboundWebhookOnNumber = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = ['FriendlyName' => $this->friendlyName];
        if ($this->inboundRequestUrl !== null) $out['InboundRequestUrl'] = $this->inboundRequestUrl;
        if ($this->inboundMethod !== null) $out['InboundMethod'] = $this->inboundMethod;
        if ($this->fallbackUrl !== null) $out['FallbackUrl'] = $this->fallbackUrl;
        if ($this->fallbackMethod !== null) $out['FallbackMethod'] = $this->fallbackMethod;
        if ($this->statusCallback !== null) $out['StatusCallback'] = $this->statusCallback;
        if ($this->stickySender !== null) $out['StickySender'] = $this->stickySender;
        if ($this->mmsConverter !== null) $out['MmsConverter'] = $this->mmsConverter;
        if ($this->smartEncoding !== null) $out['SmartEncoding'] = $this->smartEncoding;
        if ($this->scanMessageContent !== null) $out['ScanMessageContent'] = $this->scanMessageContent;
        if ($this->fallbackToLongCode !== null) $out['FallbackToLongCode'] = $this->fallbackToLongCode;
        if ($this->areaCodeGeomatch !== null) $out['AreaCodeGeomatch'] = $this->areaCodeGeomatch;
        if ($this->synchronousValidation !== null) $out['SynchronousValidation'] = $this->synchronousValidation;
        if ($this->validityPeriod !== null) $out['ValidityPeriod'] = $this->validityPeriod;
        if ($this->usecase !== null) $out['Usecase'] = $this->usecase;
        if ($this->useInboundWebhookOnNumber !== null) $out['UseInboundWebhookOnNumber'] = $this->useInboundWebhookOnNumber;

        return $out;
    }
}
