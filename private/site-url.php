<?php

declare(strict_types=1);

/**
 * Builds same-site absolute URLs from the current request.
 *
 * This deliberately does not use the database's prefSSL value: that value is
 * shared by development, staging and production, while the active protocol is
 * a property of the request reaching this server.
 */
function rthBaseUrl(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    $host = trim($host);

    // Hosts may include a development port. Reject control characters and URL
    // delimiters so a hostile Host header cannot be reflected into HTML links.
    if ($host === '' || preg_match('/[\\s\\/?#@]/', $host)) {
        $host = 'localhost';
    }

    $https = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || (string) ($_SERVER['SERVER_PORT'] ?? '') === '443'
        || (string) ($_SERVER['REQUEST_SCHEME'] ?? '') === 'https';

    // Enable this only where TLS is terminated by a known reverse proxy.
    if (getenv('RTH_TRUST_PROXY_HEADERS') === '1') {
        $forwarded = strtolower(trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))[0]));
        if ($forwarded === 'https' || $forwarded === 'http') {
            $https = $forwarded === 'https';
        }
    }

    return ($https ? 'https' : 'http') . '://' . $host;
}

function rthCurrentUrl(): string
{
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    return rthBaseUrl() . (str_starts_with($path, '/') ? $path : '/' . $path);
}
