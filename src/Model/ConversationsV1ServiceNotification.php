<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Per-service push Notification configuration — `/v1/Services/{ChatServiceSid}/Configuration/Notifications`. */
final class ConversationsV1ServiceNotification implements Model
{
    /**
     * @param array<string,mixed>|null $newMessage
     * @param array<string,mixed>|null $addedToConversation
     * @param array<string,mixed>|null $removedFromConversation
     */
    public function __construct(
        public readonly ?string $accountSid = null,
        public readonly ?string $chatServiceSid = null,
        public readonly ?array $newMessage = null,
        public readonly ?array $addedToConversation = null,
        public readonly ?array $removedFromConversation = null,
        public readonly ?bool $logEnabled = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            newMessage: isset($data['new_message']) && is_array($data['new_message']) ? $data['new_message'] : null,
            addedToConversation: isset($data['added_to_conversation']) && is_array($data['added_to_conversation']) ? $data['added_to_conversation'] : null,
            removedFromConversation: isset($data['removed_from_conversation']) && is_array($data['removed_from_conversation']) ? $data['removed_from_conversation'] : null,
            logEnabled: isset($data['log_enabled']) ? (bool) $data['log_enabled'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
