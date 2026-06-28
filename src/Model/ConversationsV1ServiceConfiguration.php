<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Per-service Conversations configuration — `/v1/Services/{ChatServiceSid}/Configuration`. */
final class ConversationsV1ServiceConfiguration implements Model
{
    /** @param array<string,string>|null $links */
    public function __construct(
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $defaultConversationCreatorRoleSid = null,
        public readonly ?string $defaultConversationRoleSid = null,
        public readonly ?string $defaultChatServiceRoleSid = null,
        public readonly ?string $url = null,
        public readonly ?array $links = null,
        public readonly ?bool $reachabilityEnabled = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            defaultConversationCreatorRoleSid: isset($data['default_conversation_creator_role_sid']) ? (string) $data['default_conversation_creator_role_sid'] : null,
            defaultConversationRoleSid: isset($data['default_conversation_role_sid']) ? (string) $data['default_conversation_role_sid'] : null,
            defaultChatServiceRoleSid: isset($data['default_chat_service_role_sid']) ? (string) $data['default_chat_service_role_sid'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
            reachabilityEnabled: isset($data['reachability_enabled']) ? (bool) $data['reachability_enabled'] : null,
        );
    }
}
