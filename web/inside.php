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
		
			if ($_SERVER['REMOTE_ADDR'] == '154.59.132.166' 
				or $_SERVER['REMOTE_ADDR'] == $prefs['prefCoderIP'] )
				{	
					include("includes/footer-debug.php");
				}
				else
				{
					echo "<h6>".$_SERVER['REMOTE_ADDR']." = ".$prefs['prefTruskaIP']."<h6>" ;
				}
		
			if (
				$_SERVER['REMOTE_ADDR'] == $prefs['prefClientIP']
				or $_SERVER['REMOTE_ADDR'] == $prefs['prefClient1IP'] ) 
				{	
					include("includes/footer-debug-client.php");
				}

			include("includes/footer-code.php"); 
	
		echo "</body>";
	?>
</html>