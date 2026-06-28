<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Service-scoped Conversation Message — Twilio Conversations v1 `IM…` under `/v1/Services/{ChatServiceSid}`. */
final class ConversationsV1ServiceConversationMessage implements Model
{
    /**
     * @param list<array<string,mixed>>|null $media
     * @param array<string,mixed>|null $delivery
     * @param array<string,string>|null $links
     */
    public function __construct(
        public readonly ?string $accountSid,
        public readonly ?string $conversationSid,
        public readonly ?string $sid,
        public readonly int $index,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $author = null,
        public readonly ?string $body = null,
        public readonly ?array $media = null,
        public readonly ?string $attributes = null,
        public readonly ?string $participantSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $url = null,
        public readonly ?array $delivery = null,
        public readonly ?array $links = null,
        public readonly ?string $contentSid = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $media = null;
        if (isset($data['media']) && is_array($data['media'])) {
            $media = [];
            foreach ($data['media'] as $row) {
                if (is_array($row)) $media[] = $row;
            }
        }
        return new self(
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            conversationSid: isset($data['conversation_sid']) ? (string) $data['conversation_sid'] : null,
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            index: (int) ($data['index'] ?? 0),
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            author: isset($data['author']) ? (string) $data['author'] : null,
            body: isset($data['body']) ? (string) $data['body'] : null,
            media: $media,
            attributes: isset($data['attributes']) ? (string) $data['attributes'] : null,
            participantSid: isset($data['participant_sid']) ? (string) $data['participant_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            url: isset($data['url']) ? (string) $data['url'] : null,
            delivery: isset($data['delivery']) && is_array($data['delivery']) ? $data['delivery'] : null,
            links: isset($data['links']) && is_array($data['links'])
                ? array_map(static fn ($v): string => (string) $v, $data['links'])
                : null,
            contentSid: isset($data['content_sid']) ? (string) $data['content_sid'] : null,
        );
    }
}
