<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ServiceConversationMessageReceipt;
use VoiceML\Model\ConversationsV1ServiceConversationMessageReceiptList;
use VoiceML\Transport;

/**
 * Read-only `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Messages/{MessageSid}/Receipts`.
 * Produced via {@see ConversationsV1ServiceConversationMessagesResource::receipts()}.
 */
final class ConversationsV1ServiceConversationMessageReceiptsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $chatServiceSid,
        private readonly string $conversationSid,
        private readonly string $messageSid,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ServiceConversationMessageReceiptList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages/{$this->messageSid}/Receipts",
            $query,
        );
        return ConversationsV1ServiceConversationMessageReceiptList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ServiceConversationMessageReceipt
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            "/v1/Services/{$this->chatServiceSid}/Conversations/{$this->conversationSid}/Messages/{$this->messageSid}/Receipts/{$sid}",
        );
        return ConversationsV1ServiceConversationMessageReceipt::fromArray($raw);
    }
}
