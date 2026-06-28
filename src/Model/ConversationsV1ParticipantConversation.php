<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** A participant's conversation summary — `/v1/ParticipantConversations`. */
final class ConversationsV1ParticipantConversation implements Model
{
    /**
     * @param array<string,mixed>|null $participantMessagingBinding
     * @param array<string,mixed>|null $conversationTimers
     * @param array<string,string>|null $links
     */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly string $conversationState,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $participantSid = null,
        public readonly ?string $participantUserSid = null,
        public readonly ?string $participantIdentity = null,
        public readonly ?array $participantMessagingBinding = null,
        public readonly ?string $conversationSid = null,
        public readonly ?string $conversationUniqueName = null,
        public readonly ?string $conversationFriendlyName = null,
        public readonly ?string $conversationAttributes = null,
        public readonly ?string $conversationDateCreated = null,
        public readonly ?string $conversationDateUpdated = null,
        public readonly ?string $conversationCreatedBy = null,
        public readonly ?array $conversationTimers = null,
        public readonly ?array $links = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationState: (string) ($data['conversation_state'] ?? ''),
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            participantSid: isset($data['participant_sid']) ? (string) $data['participant_sid'] : null,
            participantUserSid: isset($data['participant_user_sid']) ? (string) $data['participant_user_sid'] : null,
            participantIdentity: isset($data['participant_identity']) ? (string) $data['participant_identity'] : null,
            participantMessagingBinding: isset($data['participant_messaging_binding']) && is_array($data['participant_messaging_binding'])
                ? $data['participant_messaging_binding']
                : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            conversationUniqueName: isset($data['conversation_unique_name']) ? (string) $data['conversation_unique_name'] : null,
            conversationFriendlyName: isset($data['conversation_friendly_name']) ? (string) $data['conversation_friendly_name'] : null,
            conversationAttributes: isset($data['conversation_attributes']) ? (string) $data['conversation_attributes'] : null,
            conversationDateCreated: isset($data['conversation_date_created']) ? (string) $data['conversation_date_created'] : null,
            conversationDateUpdated: isset($data['conversation_date_updated']) ? (string) $data['conversation_date_updated'] : null,
            conversationCreatedBy: isset($data['conversation_created_by']) ? (string) $data['conversation_created_by'] : null,
            conversationTimers: isset($data['conversation_timers']) && is_array($data['conversation_timers'])
                ? $data['conversation_timers']
                : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
        );
    }
}
