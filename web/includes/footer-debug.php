<!-- START footer-debug.php -->
<?php
$showfooterbebug = "Yes";
if ($showfooterbebug == 'Yes') {
?>
	<style>
		.footerdebug {
			color: #F7B906;
			font-weight: 300;
			padding-top: 70px;
			background-color: beige;
		}
	</style>
	<div class="footer_sec footerdebug">

		<div class="container">

			<div class="row">

				<div class="col-12 col-lg-3">

					<div class="itemfooter">
						<h3>Access Info</h3>
						<?php
						echo "<p>";
						echo "Page id: " . $pageID . " (\$pageID)<br>";
						echo "segs [0]: " . $segs[0] . "<br>";
						echo "segs [1]: " . $segs[1] . "<br>";
						echo "segs [2]: " . $segs[2] . "<br>";
						echo "segs [3]: " . $segs[3] . "<br>";
						echo "segs [4]: " . $segs[4] . "<br>";
						echo "</p>";
						echo "<p>IP: ".$_SERVER['REMOTE_ADDR']."</p>" ;

						echo "<p>Admin User level: ". $user_role['level']." | Role ID: ".$user_role['id']."</p>";

						echo "<p><a href='".$baseURL."/wccms/dashboard.php' target='_blank'>Dashboard</a></p>" ;
						?>
					</div>
				</div>
				<?php
				$showchatonpage = 'No' ;
					if($rowpage['chatCode']) {
						$showchatonpage = ' Yes' ;  // for footer debug only
					}
				?>
				<div class="col-12 col-lg-3">
					<div class="itemfooter">
						<h3>Preference Info</h3>
						<?php
						echo "<p>";
						echo "Site Prefix : " . $prefs["prefPrefix"] . "<br>";
						echo "Is Dev site : " . $prefs["prefIsDev"] . "<br>";
						echo "Controller Debug : " . $debug . "<br>";
						echo "Show Chat Global : " . $prefs["prefShowChat"] . "<br>";
						echo "Show Chat Page : " . $showchatonpage . "<br>";
						echo "</p>";
						?>
						<h3>Spam Check Info</h3>
						<?php
						echo "<p>";
						echo "Spam Check : " . $prefs["prefIPSpamLookup"] . "<br>";
						echo "Spam Send : " . $prefs["prefspamNoSend"] . "<br>";
						echo "Spam Write : " . $prefs["prefspamNoWrite"] . "<br>";
						echo "Spam Warning : " . $prefs["prefspamWarning"] . "<br>";
						echo "</p>";
						?>
					</div>
				</div>

				<div class="col-12 col-lg-3">
					<div class="itemfooter">
						<h3>Security Info</h3>
						<?php
						echo "<p>";
						echo "SSL : " . $prefs["prefSSL"] . "<br>";
						echo "Captcha : " . $prefs["prefCaptcha"] . "<br>";
						echo "Captcha Ver : " . $prefs["prefCaptchaVer"] . "<br>";
						echo "</p>";

						?>
						<h3>SEO Info</h3>
						<?php
						echo "<p>";
						echo "Site Search : " . $prefs["prefSiteSearchOn"] . "<br>";
						echo "Page Search : " . $rowpage['pagesearch'] . "<br>";
						echo "Google Analytics On? : " . $prefs["prefGoogleAnalyticsOn"] . "<br>";
						echo "</p>";

						?>
					</div>
				</div>

				<div class="col-12 col-lg-3">
					<div class="itemfooter">
						<h3>SQL Stuff</h3>
						<?php
						echo "<p>";
						echo "Content SQL:<br>";
						echo "" . $selectcontent . "<hr>";
						echo "Content ids:<br>";
						echo "" . $contentlist . "<br>";
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


				<div class="col-12 col-lg-6">
					<div class="itemfooter">
						<h3>Page Content</h3>
						<?php
						echo "<p>";
						echo "";
						echo "</p>";

						echo "<p>HeaderPage Code: <code>".$rowpage['footercode']."</code></p>" ;
						echo "<p>FooterPage Code: <pre>".$rowpage['footercode']."</pre></p>" ;
						?>
					</div>
				</div>

			</div>

		</div>

	</div>
	<hr>
<?php
}
?>

<!-- END footer-debug.php -->
