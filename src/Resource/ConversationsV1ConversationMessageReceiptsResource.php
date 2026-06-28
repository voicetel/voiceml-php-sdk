<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Model\ConversationsV1ConversationMessageReceipt;
use VoiceML\Model\ConversationsV1ConversationMessageReceiptList;
use VoiceML\Transport;

/**
 * Read-only `/v1/Conversations/{ConversationSid}/Messages/{MessageSid}/Receipts`.
 * Bound to a parent (ConversationSid, MessageSid) pair; produced via
 * {@see ConversationsV1ConversationMessagesResource::receipts()}.
 */
final class ConversationsV1ConversationMessageReceiptsResource
{
    public function __construct(
        private readonly Transport $transport,
        private readonly string $conversationSid,
        private readonly string $messageSid,
    ) {
    }

    /** @param array<string,mixed> $query */
    public function list(array $query = []): ConversationsV1ConversationMessageReceiptList
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            "/v1/Conversations/{$this->conversationSid}/Messages/{$this->messageSid}/Receipts",
            $query,
        );
        return ConversationsV1ConversationMessageReceiptList::fromArray($raw);
    }

    public function fetch(string $sid): ConversationsV1ConversationMessageReceipt
    {
        /** @var array<string,mixed> $raw */
        $raw = $this->transport->request(
            'GET',
            "/v1/Conversations/{$this->conversationSid}/Messages/{$this->messageSid}/Receipts/{$sid}",
        );
        return ConversationsV1ConversationMessageReceipt::fromArray($raw);
    }
}
