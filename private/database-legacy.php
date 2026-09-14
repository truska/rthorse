<?php

declare(strict_types=1);

/**
 * Compatibility bootstrap for legacy MySQLi callers.
 *
 * Keep this outside the web root. New code should use Database::pdo() and
 * prepared statements; this bridge allows the existing application to run
 * unchanged while queries are migrated.
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

require_once __DIR__ . '/database.php';

$conn = Database::mysqli();

final class DB
{
    public static function query(string $query): mysqli_result|bool
    {
        return mysqli_query(Database::mysqli(), $query);
    }

    public static function connection(): mysqli
    {
        return Database::mysqli();
    }
}
