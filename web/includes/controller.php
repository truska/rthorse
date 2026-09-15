<!-- START controller -->

<?php
// Get the legacy-compatible shared database connection.
require_once __DIR__ . '/../../private/database-legacy.php';

include_once(__DIR__ . "/functions.php");
include_once(__DIR__ . "/../controllers/contentHelpers.php");

$debug = 'No';
if ($_SERVER['REMOTE_ADDR'] == '82.153.24.226'){
	$coderdebug = 'No' ;
	$debug = 'No';
}
//Get Page URL
	$pageURL = $_GET['url'];
	$pageURL = rtrim($pageURL,'/') ;

/* 2nd level urls and variables */

$pagename = basename( $pageURL );
if ($debug == 'Yes' ) {
	echo "Page Number = " . $pagename . "<br>" ;
	echo "Page URL = " . $pageURL . "<br>" ;
	echo "SERVER server_name = " . $_SERVER['SERVER_NAME'] . "<br>" ;
}
$segs = explode('/', $pageURL);

foreach ($segs as $key => $value) {
	$segs[$key] = securityCheck($value);
}

if ($debug == 'Yes' ) {
	echo "<p>Debug = Yes</p>" ;
	echo "segs[0] = " .$segs[0] . "<br>"; 
	echo "segs[1] = " .$segs[1] . "<br>"; 
	echo "segs[2] = " .$segs[2] . "<br>"; 
	echo "segs[3] = " .$segs[3] . "<br>"; 
	echo "segs[4] = " .$segs[4] . "<br>"; 
	echo "segs[5] = " .$segs[5] . "<br>";  
	echo "new url = " . $newpageURL = $segs[0] . "/" . $segs[1] . "<br>" ;
	echo "Page URL = " . $$pageURL . "<br>" ;
	echo "<hr>Gets:<br>" ;
	var_dump($_GET);
	foreach ($_GET as $getParam => $value) {
		echo $getParam . ' = ' . $value . PHP_EOL;
	}
}

	if ($debug == 'Yes' ){
		echo "<p><strong>Check DB Connection </strong><br>" ;
		$selecttest = "SELECT * FROM `preferences` ";
		echo "select for test : " . $selecttest . "<br>" ;
		$querytest = mysqli_query($conn,$selecttest);
		while ($rowtest = mysqli_fetch_assoc($querytest) ) {
			{
				echo "id " . $rowtest["id"] . ": " .$rowtest["name"] . " = " . $rowtest["value"] . "<br> " ;
			}
			
		}
		echo "</p>";
	}
		
// Load Functions
$prefs=loadPrefs($conn);
	if ($debug == 'Yes' ){
		echo "<p><strong>Check Functions</strong><br>" ;
		echo "Main Function Company Name: ".getCompanyName($prefs)."<br>" ;
	}

// Build URLs from the request, not the shared prefSSL database setting. This
// preserves development ports and automatically follows staging/live HTTPS.
require_once __DIR__ . '/../../private/site-url.php';
$baseURL = rthBaseUrl();

	if ($debug == 'Yes' ){
		echo "<p><strong>Check URL</strong><br>SSL = " . $prefs['prefSSL'] . "<br>baseURL = " . $baseURL . "</p>" ;
	}

// Select Page  
	$selectpage = "SELECT * FROM `pages` WHERE `slug` = '" . $segs[0] . "' ";
	$querypage = mysqli_query($conn,$selectpage);
	$rowpage = mysqli_fetch_assoc($querypage) ;
	$slugID = $rowpage['id'];
	$pageID = $rowpage['id'];
	$headerstyle = $rowpage['headerstyle'];
		if ($debug == 'Yes' )
		{
			echo "<p><strong>Select Page</strong><br>SQL = " .$selectpage . "<br>Page/Slug ID = " .  $slugID . " -|- " . $rowpage['name'] . "</p>";
		}

// Select Page layout
	$selectLayout = "SELECT * FROM `layout` WHERE `id` = '" . $rowpage["layout"] . "' ";
	$queryLayout = mysqli_query($conn,$selectLayout);
	$rowLayout = mysqli_fetch_assoc($queryLayout) ;
	$pageLayout = $rowLayout["url"] ;
			if ($debug == 'Yes' )
			{
				echo "<p><strong>Page layout</strong><br>id = " . $rowpage["layout"] . " | " . $pageLayout . "</p>" ;
			}

// Home Page
	if ($prefs['prefHomePage'] == $rowpage['id'])
	{$homePage = "Yes" ;} else {$homePage = "No" ;}
		$selectHomePage = "SELECT * FROM `pages` WHERE `id` = " . $prefs['prefHomePage'] . " ";
		$queryHomePage = mysqli_query($conn,$selectHomePage);
		$rowHomePage = mysqli_fetch_assoc($queryHomePage) ;
		
		$homePageURL = $rowHomePage["slug"] ;
			if ($debug == 'Yes' )
			{
				echo "<p><strong>Home Page</strong><br>Is this Home page = " . $homePage . " | Home Page Slug =  " . $homePageURL . "</p>" ;
			}

// Product Image Folders
	$lg = $prefs['prefImageFolderLarge'];
	$md = $prefs['prefImageFolderMedium'];
	$sm = $prefs['prefImageFolderSmall'];
	$xs = $prefs['prefImageFolderThumbnail'];
			if ($debug == 'Yes' )
			{
				echo "<p>" ;
					echo "Product Images folders<br>" ;
					echo "Large = " . $lg . " | Medium = " . $md . " | Small = " . $sm. " | Thumbnail = " . $xs . "" ;
				echo "</p>" ;
			}

// Page Background
	if($rowpage['imagebg'] )
	{
		$pageBackground = $rowpage['imagebg'];
	}
	else
	{
		$pageBackground = '';
	}
			if ($debug == 'Yes' )
			{
				echo "<p><strong>Background Image</strong><br>Image file name = " . $pageBackground . "</p>" ;
			}
?>
<!-- END controller -->
