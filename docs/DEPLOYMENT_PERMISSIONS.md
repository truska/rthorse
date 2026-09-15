# CMS deployment permissions

The CMS runs under a web-service account that is different from `daviddev`.
New files created by an editor, copied between sites, or unpacked from an
archive can inherit restrictive modes such as `600` or `660`. Git does not
preserve ACLs, and it only records whether a file is executable, so a code
deployment alone cannot correct this.

## Apply after deploying CMS files

Run as `root`, replacing `SITE` with the website directory, for example
`/var/www/clients/client10/web20` or `/var/www/clients/client10/web22`.

```bash
site=SITE

# Public static CMS assets must be readable by Apache/PHP-FPM.
find "$site/web/wccms/js/tinymce" -type d -exec chmod 755 {} +
find "$site/web/wccms/js/tinymce" -type f -exec chmod 644 {} +
chmod 644 "$site/web/wccms/img/no-image.jpg"

# PHP files added or changed by the deployment must be readable by the web
# service. The private directory must remain outside the public web root.
chmod 644 \
  "$site/web/wccms/include/header-code.php" \
  "$site/web/wccms/include-tinymce.php" \
  "$site/web/wccms/controllers/formField.php" \
  "$site/web/wccms/controllers/formFieldPrefs.php" \
  "$site/web/wccms/contactEdit.php" \
  "$site/web/wccms/recordAddv3.php" \
  "$site/web/wccms/recordAddv4.php" \
  "$site/web/wccms/recordEditv4.php"

# Clear PHP's compiled-file cache after PHP changes.
systemctl reload php8.4-fpm
```

## Quick verification

```bash
curl -I http://HOST/wccms/js/tinymce/tinymce.min.js
```

It must return `200`, not `403`. In the CMS edit page source, search for
`tinymce.min.js`; the TinyMCE loader is emitted from `wccms/include/header-code.php`.
