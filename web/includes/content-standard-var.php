<!-- START content-standard-var | <?php echo $rowcontent["title"]; ?> -->
<?php
$formid = 3;
$sort = "id ASC";
$usedlayout = '';
echo "<hr>";
echo "<div class='container parent-element'>" ;

	showEditButton(3, $rowcontent["id"]);

	echo "<div class='row'>";



		// --- Heading ---
		echo "<div class='col-12 content-{$rowcontent['id']}'>";
			echo "<a name='{$rowcontent['id']}'></a>";
			if ($rowcontent["showheading"] == 'Yes') {
				echo "<h1>{$rowcontent['heading']}</h1>";
			}
		echo "</div>";

			// --- Determine layout type ---
			$hasCol1 = ($rowcontent["text"] || $rowcontent["image"] || $rowcontent["subheading"]);
			$hasCol2 = ($rowcontent["text2"] || $rowcontent["image2"] || $rowcontent["subheading2"]);
			$hasCol3 = ($rowcontent["text3"] || $rowcontent["image3"] || $rowcontent["subheading3"]);

			$colCount = $hasCol1 + $hasCol2 + $hasCol3;

			// --- Render by layout ---
			switch ($colCount) {
				case 1:
					echo "<div class='col-12'>";
					renderContentColumn($rowcontent, '', $baseURL, $conn, $formid, $sort, true);
					echo "</div>";
					$usedlayout = '1c';
					break;

				case 2:
					echo "<div class='col-12 col-md-6'>";
					renderContentColumn($rowcontent, '', $baseURL, $conn, $formid, $sort, false);
					echo "</div>";

					echo "<div class='col-12 col-md-6'>";
					renderContentColumn($rowcontent, '2', $baseURL, $conn, $formid, $sort, true);
					echo "</div>";
					$usedlayout = '2c';
					break;

				case 3:
					echo "<div class='col-12 col-md-4'>";
					renderContentColumn($rowcontent, '', $baseURL, $conn, $formid, $sort, false);
					echo "</div>";

					echo "<div class='col-12 col-md-4'>";
					renderContentColumn($rowcontent, '2', $baseURL, $conn, $formid, $sort, false);
					echo "</div>";

					echo "<div class='col-12 col-md-4'>";
					renderContentColumn($rowcontent, '3', $baseURL, $conn, $formid, $sort, true);
					echo "</div>";
					$usedlayout = '3c';
					break;
			}

			//echo "<h6 class='pt-3 text-muted'>Layout used: {$usedlayout}</h6>";
	echo "</div>";

echo "</div>";
?>
<!-- END content-standard-var.php -->









<?php 
$oldcode = 'No' ;
if($oldcode == "Yes") {
?>
<!-- START content-standard-var | <?php echo $rowcontent["title"] ;?> -->

<!--
	Notes
	variable layout to encompas
	1, 2 or 3 col layout
	Control of the right Col width (only when 2 col - left and right are used)
	Uses 3 text files - if blank not used - left, middle and right.
	single Image from content 
	Gallery images from gallery - if both single image is dropped or used for listing page (i.e. all Blogs)
	Heading always across the top - or hidden and use subheading

-->	
<?php
	$formid = 3 ;
	$usedlayout = '';
	echo "<hr>" ;
	echo "<div class='container'>" ; // If not full width
		echo "<div class='row'>" ; // If not full width

			// Add full with heading
				echo "<div class='col-lg-12 content-".$rowcontent["id"]."' " ; // Heading
					echo "<a name='"	. $rowcontent["id"] . "'></a> " ;
					if ($rowcontent["showheading"] == 'Yes' )
					{
						echo "<h1>"	. $rowcontent["heading"] . "</h1>" ;
					}
					//echo "<span class='hidden-lg hidden-md hidden-sm' style='width:100%; padding-right:0px;'>" ;
				echo "</div>" ;

			// Check and set for Number of cols

			// SINGLE COL
			if(!$usedlayout) {
				// single col (full width) - middle and right text, subheading  and image blank
				//Check for Gallery Images

				//Check for single Images
				if($rowcontent["image"]  ) {$imagecol1 = 'Yes';} else {$imagecol1 = 'No';}
				if($rowcontent["image2"] ) {$imagecol2 = 'Yes';} else {$imagecol2 = 'No';}
				if($rowcontent["image3"] ) {$imagecol3 = 'Yes';} else {$imagecol3 = 'No';}

				if(!$rowcontent["text2"] AND !$rowcontent["text3"] AND !$rowcontent["image1"] AND !$rowcontent["image2"] AND !$rowcontent["subheading2"] AND !$rowcontent["subheading3"]) {
					echo "<div class='col-lg-12'>" ; // Single col layout
					if ($rowcontent["subheading"] )
					{
						echo "<h3>"	. $rowcontent["subheading"] . "</h3>" ;
					}
					if($imagecol1 == 'Yes') {
						echo "Single Image here" ;
						echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image"]."' class='img-fluid' alt='img-fluid'>" ;
					}
					if ($rowcontent["text"] )
					{
						echo ""	. $rowcontent["text"] . "" ;
					}
					//Check for Gallery Images

					// If no gallery images check for single image

						echo "<h6>Single col layout</h6>";
					echo "</div>" ;
					$usedlayout = '1c' ;
				}
			}
			
			// 2 Col
			if(!$usedlayout) {
				// 2 cols - left and right (uses width from cms for right col)
				if(!$rowcontent["text3"]  AND !$rowcontent["image3"]  AND !$rowcontent["subheading3"]) {
					// 2 col left
					echo "<div class='col-12 col-md-5'>" ; // 2 col layout LEFT COL
						echo "<p>2 cols layput - 1st col LEFT</p>" ;

						if ($rowcontent["subheading"] )
						{
							echo "<h3>"	. $rowcontent["subheading"] . "</h3>" ;
						}
						if($imagecol1 == 'Yes') 
						{
							echo "Single Image here" ;
							echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image"]."' class='img-fluid' alt=''>" ;
						}
						if ($rowcontent["text"] )
						{
							echo ""	. $rowcontent["text"] . "" ;
						}
						//Check for Gallery Images

						// If no gallery images check for single image

						echo "<h3>Two col layout # 1 col LEFT</h3>";
					echo "</div>" ;



					// 2 col right
					echo "<div class='col-12 col-md-5'>" ; // 2 col layout left - 2nd RIGHT col
						echo "<p>2 cols layout - 2nd col RIGHT</p>" ;
						if ($rowcontent["subheading"] )
						{
							echo "<h3>"	. $rowcontent["subheading"] . "</h3>" ;
						}
						if($imagecol2 == 'Yes') {
							echo "Single Image here" ;
							echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image2"]."' class='img-fluid' alt=''>" ;
						}
						if ($rowcontent["text"] )
						{
							echo ""	. $rowcontent["text"] . "" ;
						}
						//Check for Gallery Images
						// to be an include or function
						//Get main top imagers
						$selectgallerymain = "SELECT * FROM `gallery` WHERE `form_id` = '" . $formid . "' AND `record_id` = '" . $rowcontent['id'] . "' AND `showonweb` = 'Yes' AND `archived` = 0 ORDER BY ".$sort."  ";
						//echo "<p>gallery SQL: ".$selectgallerymain."</p>";
						$querygallerymain = mysqli_query($conn,$selectgallerymain);
						$rowgallerymain = mysqli_fetch_assoc($querygallerymain) ;

						// GET All Images for thumbnails
						$selectgallery = "SELECT * FROM `gallery` WHERE `form_id` = '" . $formid . "' AND `record_id` = '" . $rowcontent['id'] . "' AND `showonweb` = 'Yes' AND `archived` = 0 ORDER BY ".$sort."  ";
						$querygallery = mysqli_query($conn,$selectgallery);

						$numgallery = mysqli_num_rows($querygallery);
						//echo "<p>Number Gallery Images: ".$numgallery."</p>" ;						

							if($numgallery > 0) 
							{
								// Add gallery
								//<!-- Main zoom image -->
								echo "<a class='MagicZoom' id='zoom' title='...' href='".$baseURL."/filestore/images/content/lg/".$rowgallerymain["image"]."'>" ;
								echo "<img src='".$baseURL."/filestore/images/content/md/".$rowgallerymain["image"]."'></a>" ;
								
								// thumbnails
								echo "<div class='row  gx-3 gy-3 ' style='padding-top:10px;'>" ;	
									while ($rowgallery = mysqli_fetch_assoc($querygallery) ) {	
																								
											echo "<div class='col-12 col-md-3 col-lg-4' style=''>" ;															
												echo "<a data-zoom-id='zoom' href='".$baseURL."/filestore/images/content/lg/".$rowgallery["image"]."' data-image='".$baseURL."/filestore/images/content/md/".$rowgallery["image"]."' title='...'>" ;
												echo "<img src='".$baseURL."/filestore/images/content/md/".$rowgallery["image"]."' class='img-fluid w-100'></a>" ;
											echo "</div>" ;	
											
									}
								echo "</div>" ;
							}

						// If no gallery images check for single image
						$usedlayout = '2c' ;
					echo "</div>" ;
				}
			}

			if(!$usedlayout) {
				// 3 cols left, middle and right
				if(($rowcontent["text"]  OR $rowcontent["image"]  OR $rowcontent["subheading"]) 
					AND ($rowcontent["text2"]  OR $rowcontent["image2"]  OR $rowcontent["subheading2"])
					AND ($rowcontent["text3"]  OR $rowcontent["image3"]  OR $rowcontent["subheading3"])) {

					echo "<div class='col-lg-4'>" ; // 3 col layout
						echo "<h2>Three col layout # 1 col</h2>";
						if ($rowcontent["subheading"] )
						{
							echo "<h3>"	. $rowcontent["subheading"] . "</h3>" ;
						}
						if($imagecol1 == 'Yes') {
							echo "Single Image here" ;
							echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image"]."' class='img-fluid' alt=''>" ;
						}
						if ($rowcontent["text"] )
						{
							echo ""	. $rowcontent["text"] . "" ;
						}
					echo "</div>" ;

					echo "<div class='col-lg-4'>" ; // 3 col layout
						echo "<h2>Three col layout # 2 col</h2>";
						if ($rowcontent["subheading2"] )
						{
							echo "<h3>"	. $rowcontent["subheading2"] . "</h3>" ;
						}
						if($imagecol2 == 'Yes') {
							echo "Single Image here" ;
							echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image2"]."' class='img-fluid' alt=''>" ;
						}
						if ($rowcontent["text2"] )
						{
							echo ""	. $rowcontent["text2"] . "" ;
						}
					echo "</div>" ;

					echo "<div class='col-lg-4'>" ; // 3 col layout
						echo "<h2>Three col layout # 3 col</h2>";
						if ($rowcontent["subheading3"] )
						{
							echo "<h3>"	. $rowcontent["subheading3"] . "</h3>" ;
						}
						if($imagecol3 == 'Yes') {
							echo "Single Image here" ;
							echo "<img src='".$baseURL."/filestore/images/content/lg/".$rowcontent["image3"]."' class='img-fluid' alt=''>" ;
						}
						if ($rowcontent["text3"] )
						{
							echo ""	. $rowcontent["text3"] . "" ;
						}
					echo "</div>" ;

					$usedlayout = '3c' ;
				}
			}

			echo "<h6>Layout use: ".$usedlayout . "</h6>";
		echo "</div>" ;
	echo "</div>" ;

}
?>
<!-- END content-standard-var.php -->
	