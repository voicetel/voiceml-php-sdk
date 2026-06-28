<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Per-channel delivery receipt — Twilio Conversations v1 `DY…` resource. */
final class ConversationsV1ConversationMessageReceipt implements Model
{
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $conversationSid,
        public readonly ?string $sid,
        public readonly ?string $messageSid,
        public readonly string $status,
        public readonly int $errorCode,
        public readonly ?string $channelMessageSid = null,
        public readonly ?string $participantSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            messageSid: isset($data['message_sid']) ? (string) $data['message_sid'] : null,
            status: (string) ($data['status'] ?? ''),
            errorCode: (int) ($data['error_code'] ?? 0),
            channelMessageSid: isset($data['channel_message_sid']) ? (string) $data['channel_message_sid'] : null,
            participantSid: isset($data['participant_sid']) ? (string) $data['participant_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
