<!DOCTYPE html>
<html lang="en">
  <head>

 	<!-- START Head -->
 		<?php 
			include("includes/controller.php"); 
			include("includes/header-code.php"); 
	  	?>
	  
	<!-- End Head -->
  </head>

	<?php
		echo "<body>";

			include("includes/header.php");
		
			include("includes/menu.php");
		
			include("includes/include-announcementbar.php") ;

			// Start Main Page Body
				include("includes/page-content.php");
			// End Main Body
			
			include("includes/footer.php"); 	
		
			if (cmsAdminRequestAllowed($prefs)) {
				include("includes/footer-debug.php");
			}

			include("includes/footer-code.php"); 
	
		echo "</body>";
	?>
</html>
