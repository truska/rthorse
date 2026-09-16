<?php

declare(strict_types=1);

/**
 * Shared CMS-admin access rules.
 *
 * `prefRestrictAdminByIP` is deliberately opt-in. Sites without that
 * preference, or without any valid allowlisted IP addresses, use normal CMS
 * session authentication only.
 */
function cmsAdminAllowedIps(array $prefs): array
{
    $keys = ['prefTruskaIP', 'prefCoderIP', 'prefClientIP', 'prefClient1IP'];
    $ips = [];

    foreach ($keys as $key) {
        $ip = trim((string) ($prefs[$key] ?? ''));
        if ($ip !== '' && filter_var($ip, FILTER_VALIDATE_IP)) {
            $ips[] = $ip;
        }
    }

    return array_values(array_unique($ips));
}

function cmsAdminIpRestrictionEnabled(array $prefs): bool
{
    return ($prefs['prefRestrictAdminByIP'] ?? 'No') === 'Yes'
        && cmsAdminAllowedIps($prefs) !== [];
}

function cmsAdminRequestAllowed(array $prefs): bool
{
    if (empty($_SESSION['useremail'])) {
        return false;
    }

    if (!cmsAdminIpRestrictionEnabled($prefs)) {
        return true;
    }

    return in_array($_SERVER['REMOTE_ADDR'] ?? '', cmsAdminAllowedIps($prefs), true);
}
