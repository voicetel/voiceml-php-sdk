<?php

declare(strict_types=1);

namespace VoiceML;

use VoiceML\Exception\ValidationException;

/** Validation for resources whose public options use associative arrays. */
final class RequestRules
{
    /**
     * @param array<string,mixed>|null $query
     * @param array<string,mixed>|null $form
     */
    public static function validate(string $method, string $path, ?array $query, ?array $form): void
    {
        if ($query !== null && $method === 'GET') {
            $allowed = null;
            if (str_starts_with($path, '/v1/') || str_starts_with($path, '/v2/')) {
                $allowed = ['PageSize'];
                if ($path === '/v1/Assistants') {
                    $allowed = ['PageSize', 'Page', 'PageToken'];
                } elseif (preg_match('~/Services/[^/]+/PhoneNumbers$~', $path) === 1) {
                    $allowed = ['Page', 'PageSize'];
                } elseif (in_array($path, ['/v1/Tools', '/v1/Knowledge'], true)) {
                    $allowed[] = 'AssistantId';
                } elseif ($path === '/v1/Policies') {
                    $allowed = ['ToolId', 'KnowledgeId', 'PageSize'];
                } elseif (str_ends_with($path, '/ParticipantConversations')) {
                    $allowed = ['Identity', 'Address', 'PageSize'];
                } elseif (str_ends_with($path, '/Bindings')) {
                    $allowed = ['BindingType', 'Identity', 'PageSize'];
                } elseif (preg_match('~^/v2/(Voice|Trunking)/Numbers/[^/]+$~', $path) === 1) {
                    $allowed = ['OriginationNumber'];
                }
            } elseif (str_contains($path, '/SIP/')) {
                $allowed = ['Page', 'PageSize', 'PageToken'];
                if (preg_match('~/(Domains|CredentialLists|IpAccessControlLists|IpAddresses)\.json$~', $path) === 1) {
                    $allowed[] = 'FriendlyName';
                }
            }
            if ($allowed !== null) {
                self::keys($query, $allowed);
            }
        }
        if ($form === null) {
            return;
        }
        if (preg_match('~/SIP/Domains(?:/[^/]+)?\.json$~', $path) === 1) {
            self::keys($form, ['DomainName', 'FriendlyName', 'VoiceUrl', 'VoiceMethod', 'VoiceFallbackUrl',
                'VoiceFallbackMethod', 'VoiceStatusCallbackUrl', 'VoiceStatusCallbackMethod', 'SipRegistration',
                'Secure', 'EmergencyCallingEnabled', 'ByocTrunkSid', 'EmergencyCallerSid']);
        } elseif (preg_match('~/SIP/.+/IpAddresses/[^/]+\.json$~', $path) === 1) {
            self::keys($form, ['FriendlyName', 'IpAddress', 'CidrPrefixLength']);
        }
        foreach ($form as $key => $value) {
            if (($key === 'Method' || str_ends_with($key, 'Method')) && $value !== null
                && !in_array($value, ['GET', 'POST'], true) && $key !== 'PaymentMethod') {
                throw new ValidationException($key . ' must be GET or POST');
            }
        }
    }

    /**
     * @param array<string,mixed> $values
     * @param list<string> $allowed
     */
    private static function keys(array $values, array $allowed): void
    {
        $unknown = array_diff(array_keys($values), $allowed);
        if ($unknown !== []) {
            throw new ValidationException('unknown request parameter(s): ' . implode(', ', $unknown));
        }
    }
}
