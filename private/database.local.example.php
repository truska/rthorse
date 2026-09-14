<?php

/**
 * Local database settings template.
 *
 * Copy this file to database.local.php and supply the credentials for the
 * environment. The local file is intentionally excluded from Git.
 *
 * Environment variables take precedence over these values. This permits the
 * live server to keep credentials in its PHP-FPM/Apache environment instead.
 */
return [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'replace_me',
    'username' => 'replace_me',
    'password' => 'replace_me',
];
