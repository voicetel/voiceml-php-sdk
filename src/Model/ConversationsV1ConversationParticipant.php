<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A participant in a conversation — Twilio Conversations v1 `MB…` resource. */
final class ConversationsV1ConversationParticipant implements Model
{
    /** @param array<string,mixed>|null $messagingBinding */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $conversationSid,
        public readonly ?string $sid,
        public readonly ?string $identity = null,
        public readonly ?string $attributes = null,
        public readonly ?array $messagingBinding = null,
        public readonly ?string $roleSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
        public readonly ?int $lastReadMessageIndex = null,
        public readonly ?string $lastReadTimestamp = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            identity: isset($data['identity']) ? (string) $data['identity'] : null,
            attributes: isset($data['attributes']) ? (string) $data['attributes'] : null,
            messagingBinding: isset($data['messaging_binding']) && is_array($data['messaging_binding'])
                ? $data['messaging_binding']
                : null,
            roleSid: isset($data['role_sid']) ? (string) $data['role_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            lastReadMessageIndex: isset($data['last_read_message_index']) ? (int) $data['last_read_message_index'] : null,
            lastReadTimestamp: isset($data['last_read_timestamp']) ? (string) $data['last_read_timestamp'] : null,
        );
    }
}
