<!-- START footer-debug-client.php -->
<?php
?>
<style>
	.footerdebug {color:#F7B906; font-weight:300; padding-top:70px;}
</style>
<div class="footer_sec footerdebug">

	<div class="container">

		<div class="row">

			<div class="col-12 col-lg-6">
				<div class="itemfooter">
				<h3>Page Content</h3>
				<?php
					echo "<p>";
					echo "" . $contentitems . "<hr>" ; // Built from page-content.php
					echo "</p>";
				?>
				</div>
			</div>
			
			
			<div class="col-12 col-lg-3">

				<div class="itemfooter">
					<h3>Access Info</h3>
				<?php
					echo "<p>";
						echo "Page id: " . $pageID . " (\$pageID)<br>" ;
						echo "segs [0]: " . $segs[0] . "<br>" ;	
						echo "segs [1]: " . $segs[1] . "<br>" ;	
						echo "segs [2]: " . $segs[2] . "<br>" ;	
						echo "segs [3]: " . $segs[3] . "<br>" ;	
						echo "segs [4]: " . $segs[4] . "<br>" ;	
					echo "</p>";
					echo "<p>IP: ".$_SERVER['REMOTE_ADDR']."</p>" ;

					echo "<p>Admin User level: ". $user_role['level']."</p>";
				?>
				</div>
			</div>

			
			
			<div class="col-12 col-lg-3">
				<div class="itemfooter">
					<h3>Preference Info</h3>
				<?php                  
   					echo "<p>";
						echo "Spam Check : ".$prefs["prefCookieCheck"] . "<br>";
						echo "Cookie Check : ".$prefs["prefCookieCheck"] . "<br>";
 					echo "</p>";
				?>
					<h3>Spam Check Info</h3>
				<?php                  
 					echo "<p>";
						echo "Spam Check : ".$prefs["prefIPSpamLookup"] . "<br>";
						echo "Spam Send : ".$prefs["prefspamNoSend"] . "<br>";
						echo "Spam Write : ".$prefs["prefspamNoWrite"] . "<br>";
					echo "</p>";
				?>
				</div>
			</div>


			<div class="col-12 col-lg-6">
				<div class="itemfooter">
					<h3>META Data</h3>
					<?php
						$titlepixelsize = calculateTitlePixelWidth($titletag) ;
						$titlechar = strlen($titletag) ;
						$descpixelsize =  calculateTitlePixelWidth($metadescription) ;
						$descchar =  strlen($metadescription) ;
						$keychar =  strlen($metakeywords) ;
						echo "<p>";
						echo "<strong>Title Tag</strong> - Char: ".$titlechar." | Pixel Size: ".$titlepixelsize."<br>";
						echo $titletag . " </p>" ;

						echo "<p><strong>Meta Desc</strong> - Char: ".$descchar. " |  Pixel Size: ".$descpixelsize."<br>";
						echo $metadescription . "</p>";
						
						echo "<p><strong>Meta KeyWords</strong> - Char: " . $keychar."<br>" ;
						echo $metakeywords . "</p>";

						echo "<p>Canonical : " . $canonicaltext . "</p>";
						echo "<p>Server Request : " . htmlspecialchars($_SERVER['REQUEST_URI']) . "</p>";
					?>
				</div>
			</div>

	</div>



	

</div>
<?php
	?>

<!-- END footer-debug.php -->
