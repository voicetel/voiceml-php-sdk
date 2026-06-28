<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `/v1/Services/{ChatServiceSid}/…` — service-scoped Conversations v1 sub-tree.
 *
 * Bound to a parent `ChatServiceSid`; produced via
 * {@see ConversationsV1ServicesResource::scope()}. Mirrors the account-level
 * Conversations v1 surface and adds two service-scoped resources (Bindings,
 * Configuration). 14 sub-collections, 44 operations.
 *
 * Sub-resource map:
 *  - `conversations`                            — `/Conversations`
 *  - `conversations(sid)->messages`             — `/Conversations/{Sid}/Messages`
 *  - `conversations(sid)->messages(sid)->receipts` — read-only delivery receipts
 *  - `conversations(sid)->participants`         — `/Conversations/{Sid}/Participants`
 *  - `conversations(sid)->webhooks`             — `/Conversations/{Sid}/Webhooks`
 *  - `roles`                                    — `/Roles`
 *  - `users` / `users(sid)->conversations`      — `/Users` and per-user view
 *  - `conversationWithParticipants`             — create-only convenience
 *  - `participantConversations`                 — read-only
 *  - `bindings`                                 — read/delete-only push bindings
 *  - `configuration` / `.notifications` / `.webhooks` — per-service singletons
 */
final class ConversationsV1ServiceScopeResource
{
    public readonly ConversationsV1ServiceConversationsResource $conversations;
    public readonly ConversationsV1ServiceRolesResource $roles;
    public readonly ConversationsV1ServiceUsersResource $users;
    public readonly ConversationsV1ServiceConversationWithParticipantsResource $conversationWithParticipants;
    public readonly ConversationsV1ServiceParticipantConversationsResource $participantConversations;
    public readonly ConversationsV1ServiceBindingsResource $bindings;
    public readonly ConversationsV1ServiceConfigurationResource $configuration;

    public function __construct(Transport $transport, string $chatServiceSid)
    {
        $this->conversations = new ConversationsV1ServiceConversationsResource($transport, $chatServiceSid);
        $this->roles = new ConversationsV1ServiceRolesResource($transport, $chatServiceSid);
        $this->users = new ConversationsV1ServiceUsersResource($transport, $chatServiceSid);
        $this->conversationWithParticipants = new ConversationsV1ServiceConversationWithParticipantsResource($transport, $chatServiceSid);
        $this->participantConversations = new ConversationsV1ServiceParticipantConversationsResource($transport, $chatServiceSid);
        $this->bindings = new ConversationsV1ServiceBindingsResource($transport, $chatServiceSid);
        $this->configuration = new ConversationsV1ServiceConfigurationResource($transport, $chatServiceSid);
    }
}
