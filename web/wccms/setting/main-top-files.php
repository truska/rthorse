<?php
// Shared database bootstrap. This remains compatible with the existing MySQLi
// CMS while the application is migrated incrementally to PDO.
require_once dirname(__FILE__) . '/../../../private/database-legacy.php';
require_once (dirname(__FILE__) . '/../include/session.php');
require_once (dirname(__FILE__) . '/../include/functions.php');

// Check and if set turn Error Reporting on for CMS Globally
if (isset($prefs['prefShowErrorsCMS']) && $prefs['prefShowErrorsCMS'] === 'Yes') {
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
} else {
   error_reporting(0); // production default
   ini_set('display_errors', 0);
}

require_once (dirname(__FILE__) . '/../controllers/cmsUser.php'); // User class (admin)
require_once (dirname(__FILE__) . '/../logrecord.php');
require_once (dirname(__FILE__) . '/../controllers/User.php'); // User class (front)
require_once (dirname(__FILE__) . '/../controllers/menu.php');
require_once (dirname(__FILE__) . '/../controllers/imageResizer.php');
//require_once (dirname(__FILE__) . '/../controllers/formField.php');
require_once (dirname(__FILE__) . '/../controllers/preferences.php');
require_once (dirname(__FILE__) . '/../controllers/recordView.php');
require_once (dirname(__FILE__) . '/../controllers/recordEdit.php');
require_once (dirname(__FILE__) . '/../controllers/recordAdd.php');
require_once (dirname(__FILE__) . '/../controllers/formatHelpers.php');

if($pageType == 'prefs') {
   require_once (dirname(__FILE__) . '/../controllers/formFieldPrefs.php');
}
else
{
   require_once (dirname(__FILE__) . '/../controllers/formField.php');
}

$prefs = loadPrefs($conn);
// $prefshop = loadShopPrefs();

// Build URLs from the request, not the shared prefSSL database setting.
require_once dirname(__FILE__) . '/../../../private/site-url.php';
$baseURL = rthBaseUrl();
$BASE_URL = $baseURL;

// Check if user is logged in. Use the executed script name rather than the
// request path so /wccms/ and /wccms/index.php behave identically.
$currentCmsScript = basename($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF']);
if (!isset($_SESSION["useremail"])) {
   if (
      !in_array($currentCmsScript, ['index.php', 'reset.php', '2fa.php'], true)
   ) {
      header("Location: " . $BASE_URL . "/wccms/index.php");
      exit();
   }
} else {
   $USER = new CMSUser($_SESSION['useremail']);
   $user = $USER->getUser();
}

if (!isset($userid) && isset($_SESSION['userid'])) {
   $userid = $_SESSION['userid'];
}
