<?php

declare(strict_types=1);

namespace VoiceML\Model;

/** Body for `POST /v1/Services/{ChatServiceSid}/Configuration/Notifications`. */
final class UpdateConversationsV1ServiceNotificationRequest
{
    public function __construct(
        public readonly ?bool $logEnabled = null,
        public readonly ?bool $newMessageEnabled = null,
        public readonly ?string $newMessageTemplate = null,
        public readonly ?string $newMessageSound = null,
        public readonly ?bool $newMessageBadgeCountEnabled = null,
        public readonly ?bool $newMessageWithMediaEnabled = null,
        public readonly ?string $newMessageWithMediaTemplate = null,
        public readonly ?bool $addedToConversationEnabled = null,
        public readonly ?string $addedToConversationTemplate = null,
        public readonly ?string $addedToConversationSound = null,
        public readonly ?bool $removedFromConversationEnabled = null,
        public readonly ?string $removedFromConversationTemplate = null,
        public readonly ?string $removedFromConversationSound = null,
    ) {
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->logEnabled !== null) $out['LogEnabled'] = $this->logEnabled;
        if ($this->newMessageEnabled !== null) $out['NewMessage.Enabled'] = $this->newMessageEnabled;
        if ($this->newMessageTemplate !== null) $out['NewMessage.Template'] = $this->newMessageTemplate;
        if ($this->newMessageSound !== null) $out['NewMessage.Sound'] = $this->newMessageSound;
        if ($this->newMessageBadgeCountEnabled !== null) $out['NewMessage.BadgeCountEnabled'] = $this->newMessageBadgeCountEnabled;
        if ($this->newMessageWithMediaEnabled !== null) $out['NewMessage.WithMedia.Enabled'] = $this->newMessageWithMediaEnabled;
        if ($this->newMessageWithMediaTemplate !== null) $out['NewMessage.WithMedia.Template'] = $this->newMessageWithMediaTemplate;
        if ($this->addedToConversationEnabled !== null) $out['AddedToConversation.Enabled'] = $this->addedToConversationEnabled;
        if ($this->addedToConversationTemplate !== null) $out['AddedToConversation.Template'] = $this->addedToConversationTemplate;
        if ($this->addedToConversationSound !== null) $out['AddedToConversation.Sound'] = $this->addedToConversationSound;
        if ($this->removedFromConversationEnabled !== null) $out['RemovedFromConversation.Enabled'] = $this->removedFromConversationEnabled;
        if ($this->removedFromConversationTemplate !== null) $out['RemovedFromConversation.Template'] = $this->removedFromConversationTemplate;
        if ($this->removedFromConversationSound !== null) $out['RemovedFromConversation.Sound'] = $this->removedFromConversationSound;
        return $out;
    }
}
