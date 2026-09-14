<?php

declare(strict_types=1);

/**
 * Shared database factory.
 *
 * New code should use Database::pdo() and prepared statements. Database::mysqli()
 * exists only to keep the established CMS and site queries working while they are
 * migrated incrementally.
 */
final class Database
{
    private static ?mysqli $mysqli = null;
    private static ?PDO $pdo = null;
    private static ?array $config = null;

    /** @return array{host:string,port:int,database:string,username:string,password:string} */
    private static function config(): array
    {
        if (self::$config !== null) {
            return self::$config;
        }

        $localConfig = __DIR__ . '/database.local.php';
        $local = is_file($localConfig) ? require $localConfig : [];
        if (!is_array($local)) {
            throw new RuntimeException('Database local configuration must return an array.');
        }

        $values = [
            'host' => self::environment('RTH_DB_HOST', $local['host'] ?? null),
            'port' => self::environment('RTH_DB_PORT', $local['port'] ?? 3306),
            'database' => self::environment('RTH_DB_DATABASE', $local['database'] ?? null),
            'username' => self::environment('RTH_DB_USERNAME', $local['username'] ?? null),
            'password' => self::environment('RTH_DB_PASSWORD', $local['password'] ?? null),
        ];

        foreach (['host', 'database', 'username', 'password'] as $key) {
            if (!is_string($values[$key]) || $values[$key] === '' || $values[$key] === 'replace_me') {
                throw new RuntimeException("Database setting '{$key}' is missing.");
            }
        }

        self::$config = [
            'host' => $values['host'],
            'port' => (int) $values['port'],
            'database' => $values['database'],
            'username' => $values['username'],
            'password' => $values['password'],
        ];

        return self::$config;
    }

    private static function environment(string $name, mixed $fallback): mixed
    {
        $value = getenv($name);
        return $value === false || $value === '' ? $fallback : $value;
    }

    public static function mysqli(): mysqli
    {
        if (self::$mysqli instanceof mysqli) {
            return self::$mysqli;
        }

        $config = self::config();
        self::$mysqli = mysqli_connect(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['database'],
            $config['port']
        );

        if (!self::$mysqli) {
            throw new RuntimeException('Database connection failed.');
        }

        mysqli_set_charset(self::$mysqli, 'utf8mb4');
        return self::$mysqli;
    }

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = self::config();
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['database']
        );

        self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$pdo;
    }
}
