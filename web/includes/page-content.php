<!-- START page-content -->

<?php
	//echo "pageid: ".$rowpage['id']."<br>";

	$sort = " `sort` " ;


	$selectcontent = "SELECT * FROM `content` WHERE `page` = '" . $rowpage['id'] . "' AND `showonweb` = 'Yes' AND `archived` = 0 ORDER BY ".$sort."  ";
	$querycontent = mysqli_query($conn,$selectcontent);
	$num_rows = mysqli_num_rows($querycontent);
	//echo "rows = " .$num_rows . "<br>";
	//echo "SQL = " .$selectcontent . "<br>";


	$contentcount = 1 ;
	/*
		echo "<p>" ;
		echo "Sort: ".$sort."<br>" ;
		echo "Cookie : ".$cookie."<br>" ;
		echo "SQL: ".$selectcontent."<br>" ;
		echo "</p>" ;
	*/

	while ($rowcontent = mysqli_fetch_assoc($querycontent) )
	{
		// GET LAYOUT
		//echo $rowcontent["title"] . " | " . $rowcontent["layout"] . "<br>" ;
		$selectcontentlayout = "SELECT `id`, `url`,`name` FROM `layout` WHERE `id` = " . $rowcontent['layout'] . " ";
		$querycontentlayout = mysqli_query($conn,$selectcontentlayout);
		$rowcontentlayout = mysqli_fetch_assoc($querycontentlayout) ;

		$layoutname = $rowcontentlayout["name"] ;
			//echo $rowpage["title"] . " | " . $pageLayout . "<br>" ;
			//echo $rowcontent["title"] . " | " . $rowcontent["layout"] . " | " . $rowcontentlayout["url"] . " | " . $rowcontentlayout["id"] . "<br>" ;
			//echo "padding: " . $rowcontent["padding-top"] . " | " . $rowcontent["padding-bottom"] . " | ". $rowcontent["id"]."<br>" ;

		//	include("includes/" . $rowcontentlayout["url"] . "") ; 
		
		$contentcount ++ ;

		if ($rowcontent["padding-top"] > 0 ) {
			$paddingtop = "padding-top:".$rowcontent["padding-top"]."px"; 
		}
		else
		{
			$paddingtop = "0px";
		}
		if ($rowcontent["padding-bottom"] > 0 ) {
			$paddingbottom = "padding-bottom:". $rowcontent["padding-bottom"]."px"; 
		}
		else
		{
			$paddingbottom = "0px";
		}

		// Mobile
		if ($rowcontent["padding-top-mobile"] > 0 ) {
			$paddingtopmobile = "padding-top:".$rowcontent["padding-top-mobile"]."px"; 
		}
		else
		{
			$paddingtopmobile = "0px";
		}
		if ($rowcontent["padding-bottom-mobile"] > 0 ) {
			$paddingbottommobile = "padding-bottom:". $rowcontent["padding-bottom-mobile"]."px"; 
		}
		else
		{
			$paddingbottommobile = "0px";
		}

		?>
			<style>
				.content-<?php echo $rowcontent["id"];?> {
					<?php echo $paddingtop ; ?>;
					<?php echo $paddingbottom ; ?>;
				}
					
					@media  (max-width: 767px) {
						.content-<?php echo $rowcontent["id"];?> {
						<?php echo $paddingtopmobile ; ?>;
						<?php echo $paddingbottommobile ; ?>;
						}
					}
			</style>
		<?php

		include("includes/" . $rowcontentlayout["url"] . "") ; 
			
			// Build Coding help message
			$contentitems = $contentitems . "<br>".$rowcontent["id"]." - ". $layoutname ." - ".$rowcontentlayout["url"]." [".$rowcontentlayout["id"]."]" ;
	}
?>

<!-- END page-content -->