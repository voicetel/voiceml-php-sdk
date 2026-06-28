<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Service-scoped push Binding — Twilio Conversations v1 `BS…` under `/v1/Services/{ChatServiceSid}`. */
final class ConversationsV1ServiceBinding implements Model
{
    /** @param list<string>|null $messageTypes */
    public function __construct(
        public readonly ?string $sid,
        public readonly string $bindingType,
        public readonly ?string $accountSid = null,
        public readonly ?string $chatServiceSid = null,
        public readonly ?string $credentialSid = null,
        public readonly ?string $dateCreated = null,
        public readonly ?string $dateUpdated = null,
        public readonly ?string $endpoint = null,
        public readonly ?string $identity = null,
        public readonly ?array $messageTypes = null,
        public readonly ?string $url = null,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $types = null;
        if (isset($data['message_types']) && is_array($data['message_types'])) {
            $types = array_values(array_map(static fn ($v): string => (string) $v, $data['message_types']));
        }
        return new self(
            sid: isset($data['sid']) ? (string) $data['sid'] : null,
            bindingType: (string) ($data['binding_type'] ?? ''),
            accountSid: isset($data['account_sid']) ? (string) $data['account_sid'] : null,
            chatServiceSid: isset($data['chat_service_sid']) ? (string) $data['chat_service_sid'] : null,
            credentialSid: isset($data['credential_sid']) ? (string) $data['credential_sid'] : null,
            dateCreated: isset($data['date_created']) ? (string) $data['date_created'] : null,
            dateUpdated: isset($data['date_updated']) ? (string) $data['date_updated'] : null,
            endpoint: isset($data['endpoint']) ? (string) $data['endpoint'] : null,
            identity: isset($data['identity']) ? (string) $data['identity'] : null,
            messageTypes: $types,
            url: isset($data['url']) ? (string) $data['url'] : null,
        );
    }
}
