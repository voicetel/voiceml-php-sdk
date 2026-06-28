<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Paginated `/v1/Services/{ChatServiceSid}/Conversations/{ConversationSid}/Messages/{MessageSid}/Receipts` response. */
final class ConversationsV1ServiceConversationMessageReceiptList implements Model
{
    /** @param list<ConversationsV1ServiceConversationMessageReceipt> $deliveryReceipts */
    public function __construct(
        public readonly array $deliveryReceipts,
        public readonly VoiceV1Meta $meta,
    ) {
    }

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $items = [];
        foreach ((array) ($data['delivery_receipts'] ?? []) as $row) {
            if (is_array($row)) $items[] = ConversationsV1ServiceConversationMessageReceipt::fromArray($row);
        }
        return new self(
            deliveryReceipts: $items,
            meta: VoiceV1Meta::fromArray(is_array($data['meta'] ?? null) ? $data['meta'] : []),
        );
    }
}
