<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Service-scoped per-user view of a Conversation — `/v1/Services/{ChatServiceSid}/Users/{UserSid}/Conversations`. */
final class ConversationsV1ServiceUserConversation implements Model
{
    /**
     * @param array<string,mixed>|null $timers
     * @param array<string,string>|null $links
     */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly string $conversationState,
        public readonly string $notificationLevel,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $conversationSid = null,
        public readonly ?int $unreadMessagesCount = null,
        public readonly ?int $lastReadMessageIndex = null,
        public readonly ?string $participantSid = null,
        public readonly ?string $userSid = null,
        public readonly ?string $friendlyName = null,
        public readonly ?array $timers = null,
        public readonly ?string $attributes = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $createdBy = null,
        public readonly ?string $uniqueName = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationState: (string) ($data['conversation_state'] ?? ''),
            notificationLevel: (string) ($data['notification_level'] ?? ''),
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            unreadMessagesCount: isset($data['unread_messages_count']) ? (int) $data['unread_messages_count'] : null,
            lastReadMessageIndex: isset($data['last_read_message_index']) ? (int) $data['last_read_message_index'] : null,
            participantSid: isset($data['participant_sid']) ? (string) $data['participant_sid'] : null,
            userSid: isset($data['user_sid']) ? (string) $data['user_sid'] : null,
            friendlyName: isset($data['friendly_name']) ? (string) $data['friendly_name'] : null,
            timers: isset($data['timers']) && is_array($data['timers']) ? $data['timers'] : null,
            attributes: isset($data['attributes']) ? (string) $data['attributes'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            createdBy: isset($data['created_by']) ? (string) $data['created_by'] : null,
            uniqueName: isset($data['unique_name']) ? (string) $data['unique_name'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
        );
    }
}
