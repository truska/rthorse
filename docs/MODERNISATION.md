# Roundthorn Sport Horses modernisation

## Baseline — 11 September 2026

- Target runtime: PHP 8.4.
- Bootstrap: current official v5.3.8 CSS and JavaScript bundle, loaded from the
  official jsDelivr CDN with integrity verification.
- Verified CLI runtime: PHP 8.4.17 with `mysqli`, `PDO`, and `pdo_mysql`.
- Database: MariaDB 11.8.3; the migrated application account connects.
- PHP syntax: all PHP files passed `php8.4 -l`.
- The application contains no removed `mysql_*` extension calls; it is currently MySQLi based.

## Database approach

1. Credentials must be supplied either with `RTH_DB_HOST`, `RTH_DB_PORT`,
   `RTH_DB_DATABASE`, `RTH_DB_USERNAME`, and `RTH_DB_PASSWORD` environment
   variables, or in `private/database.local.php` copied from the tracked
   example. Environment variables take precedence.
2. `private/database.php` is the only connection factory. It provides one
   request-scoped MySQLi connection for legacy code and one PDO connection for
   new/migrated code. `private/database-legacy.php` is the MySQLi compatibility
   bootstrap used while migration is in progress.
3. New SQL must use PDO prepared statements. Existing MySQLi use is retained
   only as a migration bridge; do not add further MySQLi queries.
4. Convert public read paths first, then CMS authenticated writes, testing each
   route before proceeding. Dynamic table/column identifiers need an allowlist;
   placeholders only protect values.

## Priority work

1. Replace interpolated SQL on public search, page routing, listing and detail pages.
2. Audit CMS login, password hashes, reset tokens, 2FA and CSRF protection.
3. Secure uploads/image processing and dynamic CMS database operations.
4. Upgrade Bootstrap/assets separately, with visual checks of the public site and CMS.

## Resolved legacy shop dependency

The public site does not use shop preferences, text or modules. The obsolete
`preferences_shop`, `shoptext`, and `preferences_modules` loaders, along with
their unused currency/collection assignments, were removed rather than adding
or altering redundant database tables.

The imported `banner` table uses `page`, optional `section`, and `sort` for
placement. A separate controller query requiring a non-existent `position`
column was unused and removed; the normal banner renderer already uses the
imported schema.

Development sites on a non-standard port must build internal URLs from
`HTTP_HOST`, not `SERVER_NAME`, so image and asset links retain that port.

Horse detail pages previously stopped before rendering their galleries because
an obsolete, visually commented `successstories` query still executed in PHP.
That unused query was removed. The main gallery for horse 868 now renders its
four imported image records; a separate pedigree-thumbnail path still needs
normalising because some legacy `folder_name` values already include `images/`.

The CMS now uses the same shared database compatibility bootstrap as the public
site. Login, dashboard, menu navigation, and a product edit were verified on
the migrated environment. CMS authentication and write workflows still require
the staged security and PDO migration recorded in `MIGRATION_AUDIT.md`.

CMS authentication now stores new passwords with `password_hash()` and upgrades
an existing MD5 password to a modern hash on its next successful login. Password
reset tokens use `random_bytes()`, 2FA codes use `random_int()`, and successful
CMS logins regenerate the session ID. Verify a real login, reset, and 2FA flow
separately before production release.

## Deployment checklist

- Put production credentials in the PHP-FPM/Apache environment, or create
  `private/database.local.php` from the example with owner-readable, non-public
  permissions.
- Do not commit any local credential file.
- Confirm the PHP-FPM handler can read `private/database.local.php` and reach
  MariaDB.
- Run `find web private -type f -name '*.php' -print0 | xargs -0 -n1 php8.4 -l`.
- Test public routes, CMS login, a CMS update, uploads, search and error pages.
