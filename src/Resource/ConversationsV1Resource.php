<?php

declare(strict_types=1);

namespace VoiceML\Resource;

use VoiceML\Transport;

/**
 * `$client->conversationsV1` — top-level holder for the Twilio
 * Conversations v1 (conversations.twilio.com/v1) family.
 *
 * Surface map (15 namespaced resources, ~50 ops):
 *  - `conversations`                     — `/v1/Conversations`
 *  - `conversations(sid)->messages`      — `/v1/Conversations/{Sid}/Messages`
 *  - `conversations(sid)->messages(sid)->receipts` — read-only delivery receipts
 *  - `conversations(sid)->participants`  — `/v1/Conversations/{Sid}/Participants`
 *  - `conversations(sid)->webhooks`      — `/v1/Conversations/{Sid}/Webhooks`
 *  - `roles`                             — `/v1/Roles`
 *  - `users` / `users(sid)->conversations` — `/v1/Users`
 *  - `credentials`                       — `/v1/Credentials`
 *  - `configuration` / `.webhooks` / `.addresses` — account-wide config
 *  - `participantConversations`          — `/v1/ParticipantConversations`
 *  - `conversationWithParticipants`      — create-only convenience
 *  - `services`                          — `/v1/Services`
 */
final class ConversationsV1Resource
{
    public readonly ConversationsV1ConversationsResource $conversations;
    public readonly ConversationsV1RolesResource $roles;
    public readonly ConversationsV1UsersResource $users;
    public readonly ConversationsV1CredentialsResource $credentials;
    public readonly ConversationsV1ConfigurationResource $configuration;
    public readonly ConversationsV1ParticipantConversationsResource $participantConversations;
    public readonly ConversationsV1ConversationWithParticipantsResource $conversationWithParticipants;
    public readonly ConversationsV1ServicesResource $services;

    public function __construct(Transport $transport)
    {
        $this->conversations = new ConversationsV1ConversationsResource($transport);
        $this->roles = new ConversationsV1RolesResource($transport);
        $this->users = new ConversationsV1UsersResource($transport);
        $this->credentials = new ConversationsV1CredentialsResource($transport);
        $this->configuration = new ConversationsV1ConfigurationResource($transport);
        $this->participantConversations = new ConversationsV1ParticipantConversationsResource($transport);
        $this->conversationWithParticipants = new ConversationsV1ConversationWithParticipantsResource($transport);
        $this->services = new ConversationsV1ServicesResource($transport);
    }
}
