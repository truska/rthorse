-- Adds the opt-in CMS IP allowlist setting introduced in commit 8a03e81.
-- The default is No: CMS authentication uses the normal logged-in session.
-- This statement does not overwrite a value that already exists.

INSERT INTO preferences (
    name, label, value, notes, prefCat, field, class, userlevel, sort,
    comment, placeholder, required, max, min, step, tooltip, logmask,
    showoncms, showonweb, allowedit, archived
)
SELECT
    'prefRestrictAdminByIP',
    'Restrict CMS access by IP address',
    'No',
    'When enabled, a logged-in CMS user must also match one of the configured CMS IP addresses.',
    6, 17, '', 30, 101,
    '', '', 'No', 0, 0, 0, '', 0,
    'Yes', 'No', 'Yes', 0
WHERE NOT EXISTS (
    SELECT 1
    FROM preferences
    WHERE name = 'prefRestrictAdminByIP'
);
