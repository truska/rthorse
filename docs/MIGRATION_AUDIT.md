# PHP 8.4 Migration Audit

## Executive summary

The public site is now operational on PHP 8.4 and MariaDB 11.8.3. The database
connection, public page routing, image delivery, Bootstrap assets, homepage,
the main internal pages, and twelve sampled horse-detail pages were verified.
All 101 PHP files pass `php8.4 -l`.

The migration is not complete. The public CMS entry point currently returns
HTTP 500, and the CMS contains the greatest concentration of security and
compatibility debt. The public site also suppresses a set of PHP 8.4 warnings
which should be fixed before production deployment, even though they do not
currently prevent rendering.

## Scope and method

- PHP 8.4.17 syntax lint of all PHP files.
- Read-only database connectivity, schema, and gallery metadata checks.
- HTTP checks of public pages and twelve public horse-detail routes.
- PHP 8.4 execution with visible errors for representative public routes.
- Static review of public and CMS database access, authentication, uploads,
  dynamic includes, and obsolete table references.

No authenticated CMS operation, data write, upload, email send, or destructive
database action was performed.

## Verified working baseline

| Area | Result |
| --- | --- |
| PHP runtime | PHP 8.4.17; `mysqli`, `PDO`, and `pdo_mysql` available. |
| Database | The migrated application account connects to MariaDB 11.8.3. |
| Syntax | 101 PHP files lint clean with `php8.4 -l`. |
| Public routes | Homepage, About, Contact, Horses, and sampled horse-detail routes returned HTTP 200 and complete HTML. |
| Images | `filestore` is present and static images return HTTP 200. Generated asset URLs retain the development port. |
| Bootstrap | Official Bootstrap v5.3.8 CSS and bundle are loaded with SRI. |

## Priority findings

### Resolved — CMS bootstrap and basic operation

The CMS bootstrap originally required the imported `private/dbcon.php` and
`private/db.php` directly, which caused HTTP 500 under the `web20:client10`
PHP-FPM pool. It now uses `database-legacy.php`, matching the public site. CMS
redirects also preserve the current development host and port.

Evidence: [main-top-files.php](../web/wccms/setting/main-top-files.php:2),
[database-legacy.php](../private/database-legacy.php:1).

Verified: login, dashboard, CMS menu navigation, and a product edit work on the
migrated environment. Logout, password reset, 2FA, and upload remain separate
workflow tests.

### P0 — CMS passwords, reset tokens, and 2FA need security migration

The CMS hashes passwords with MD5, creates reset tokens with MD5 of predictable
input, and generates 2FA codes with `rand()`. MD5 is not suitable for password
storage, and reset/verification secrets need cryptographically secure random
values. Existing hashes require a staged migration: verify legacy MD5 once,
then replace it with `password_hash()` on successful login; new and reset
passwords must use `password_hash()` immediately.

Evidence: [index.php](../web/wccms/index.php:27),
[index.php](../web/wccms/index.php:61),
[changepasswordv2.php](../web/wccms/changepasswordv2.php:38),
[cmsUser.php](../web/wccms/controllers/cmsUser.php:87).

Action: design and test a credential migration before enabling the CMS on any
internet-facing environment. Add `password_verify()`, `random_bytes()` tokens,
`random_int()` codes, session-ID regeneration on login, expiry and one-use
reset tokens, login rate limiting, and CSRF protection for state-changing forms.

### P1 — SQL construction is widespread, especially in CMS write paths

Public and CMS code builds SQL by concatenating values into strings. Some paths
call `mysqli_real_escape_string()`, but that is inconsistent and does not make
dynamic identifiers safe. Several CMS functions derive tables and columns from
form metadata, then build `UPDATE`, `INSERT`, `ALTER TABLE`, and `SHOW COLUMNS`
statements dynamically.

Evidence: [controller.php](../web/includes/controller.php:93),
[content-search.php](../web/includes/content-search.php:38),
[recordBulkUpdatev1.php](../web/wccms/recordBulkUpdatev1.php:347),
[admin-database-fields.php](../web/wccms/admin-database-fields.php:571),
[contactEdit.php](../web/wccms/contactEdit.php:67).

Action: use the PDO factory for values and prepared statements. For table and
column names, resolve only against a server-side allowlist from trusted form
metadata; SQL placeholders cannot bind identifiers. Convert public read routes
first, then one CMS write workflow at a time with regression tests.

### P1 — PHP 8.1+ turns schema/query defects into exceptions

The missing shop table, stale `banner.position` condition, and obsolete
`successstories` query were surfaced because MySQLi now throws exceptions by
default. More dormant schema mismatch paths may therefore become HTTP 500 when
their associated feature is used.

The active public blockers found so far were removed without changing the
imported schema. Dormant CMS references remain, including `preferences_shop` in
the CMS helper and old migration tools that address the legacy `horses` table.

Evidence: [mysqli error-mode documentation](https://www.php.net/mysqli-driver.report-mode.php),
[CMS functions](../web/wccms/include/functions.php:113),
[recordDataMigration.php](../web/wccms/recordDataMigration.php:11).

Action: add structured exception logging in the shared database layer; do not
globally silence strict MySQLi errors. Build a route-by-route test list and
either remove unused legacy code or adapt it to the imported schema.

### P1 — Upload and image-processing surface needs a focused audit

The CMS contains multiple upload implementations. Some modern paths validate an
image with `getimagesize()`, but older upload scripts use the original filename,
weak random suffixes, direct file moves, and image reprocessing. The upload
directory is executable/traversable by the site account and is intentionally
outside Git, so it needs a specific policy.

Evidence: [recordEditv4.php](../web/wccms/recordEditv4.php:97),
[upload_images.php](../web/wccms/upload_images.php:68),
[upload.php](../web/wccms/upload.php:38),
[image_processor.php](../web/wccms/controllers/image_processor.php:20).

Action: consolidate on the v4 upload path after CMS login is repaired; enforce
MIME/content validation, dimension and file-size limits, server-generated names
via `random_bytes()`, a strict image extension allowlist, and no script
execution in upload folders. Test SVG handling separately; do not allow it by
default.

### P2 — Suppressed PHP 8.4 warnings indicate incomplete data assumptions

Representative public routes render with production errors hidden, but visible
error testing exposed missing optional keys and absent include files. These do
not currently stop the public pages, but they can pollute output if error
display is ever enabled and create future PHP 9 migration risk.

| Finding | Evidence | Recommended treatment |
| --- | --- | --- |
| Missing optional preferences: `prefAddress`, `prefShowChat`, copyright/platform fields | [header.php](../web/includes/header.php:114), [metadata.php](../web/includes/metadata.php:106), [footer-scripts.php](../web/includes/footer-scripts.php:11) | Use `?? ''`/explicit defaults or add documented database defaults. |
| Missing optional content keys and uninitialised accumulator | [page-content.php](../web/includes/page-content.php:58), [page-content.php](../web/includes/page-content.php:92) | Initialise variables and use null coalescing for optional fields. |
| Missing `custom-css.php` and `include-announcementbar.php` includes | [header-code.php](../web/includes/header-code.php:77), [inside.php](../web/inside.php:21) | Restore them if intended, otherwise remove the includes or guard them with `is_file()`. |
| Detail-page schema assumptions | [metadata.php](../web/includes/metadata.php:66) | Use `?? ''` for optional product metadata/keyword fields. |
| Pedigree thumbnail builds `images/images/...` for legacy folder values | [contentHelpers.php](../web/controllers/contentHelpers.php:257) | Normalise `folder_name` before concatenating the path. |

### P2 — Debug output and developer-IP branches should be removed before live use

The public entry point contains IP-based debug branches. On matching IPs it
renders SQL, route, preference, and CMS-related diagnostic information directly
into HTML. This is useful during migration but fragile as an access-control
mechanism and should not remain in a live deployment.

Evidence: [inside.php](../web/inside.php:29), [footer-debug.php](../web/includes/footer-debug.php:1),
[controller.php](../web/includes/controller.php:11).

Action: replace browser debug output with a configuration-controlled logger
whose output is outside the web root. Remove hard-coded IPs and ensure
`display_errors=Off` in live PHP-FPM settings.

### P2 — URL and host handling requires production hardening

Using `HTTP_HOST` correctly preserves a non-standard development port, but it
must be paired with an Apache virtual-host allowlist/canonical host redirect in
production to avoid reflecting arbitrary Host headers in generated links. The
current code also determines scheme from a database preference rather than the
actual trusted proxy/request context.

Evidence: [controller.php](../web/includes/controller.php:76),
[.htaccess](../web/.htaccess:25).

Action: set the canonical public host in environment configuration, validate
forwarded-proto only from a trusted proxy, and use the configuration for public
URLs in production. Keep the current request-host behaviour only for local/dev
port support if required.

### P3 — Maintainability and deployment gaps

- No dependency manifest, test suite, or automated browser smoke test is
  present. Lint alone cannot detect schema or template failures.
- jQuery 3.6 is loaded globally despite the reviewed Bootstrap code using the
  Bootstrap 5 bundle APIs. Remove it only after checking stored CMS snippets.
- The site is on PHP 8.4, which remains security-supported through 31 December
  2028, but production needs a routine point-release patching policy.
- User-generated media is correctly excluded from Git, but needs a documented
  backup/restore and deployment procedure separate from application code.

## Recommended sequence

1. Repair CMS database bootstrap and establish an authenticated CMS test plan.
2. Migrate authentication/reset/2FA/session security before exposing the CMS.
3. Add exception logging and eliminate public PHP warnings/missing includes.
4. Convert public read queries to PDO prepared statements and add route smoke
   tests.
5. Consolidate and secure uploads; then convert CMS write paths with identifier
   allowlists and CSRF protection.
6. Retire unused v3/migration scripts after confirming no operational use.
7. Add a deployment checklist: secrets, media restore, database backup, PHP
   lint, smoke tests, and rollback.

## Sources

1. PHP Manual, [mysqli error reporting mode](https://www.php.net/mysqli-driver.report-mode.php). PHP 8.1 sets strict MySQLi error reporting by default; used to explain why legacy schema defects now fail loudly.
2. PHP Manual, [Migrating from PHP 8.3.x to PHP 8.4.x](https://www.php.net/migration84). Current PHP 8.4 compatibility reference.
3. PHP.net, [supported versions](https://www.php.net/supported-versions.php). PHP 8.4 support lifecycle.
4. OWASP, [Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html). Guidance for replacing MD5 password storage with adaptive password hashing.
5. Local source and runtime evidence: files linked throughout this report; PHP 8.4 lint, read-only database checks, and HTTP smoke checks performed on 11 September 2026.
