<!-- START metadata -->    
    
    <?php
		if ($rowpage["titletag"])
		{
			$titletag = "" . $rowpage["titletag"] . "" ;
		}
		else
		{
			// Get data from page 1st content element
			$selectbannertitle = "SELECT `title` FROM `banner` WHERE `page` = '" . $rowpage['id'] . "' AND `showonweb` = 'Yes' ORDER BY ".$sort." LIMIT 1 ";
			$querybannertitle = mysqli_query($conn,$selectbannertitle);
			$rowbannertitle = mysqli_fetch_assoc($querybannertitle);
			
			if($rowbannertitle["title"]) {
				$titletag = "" . $rowbannertitle["titletag"] . " | RxSource" ;
			}
			else
			{
				$titletag = "" . $rowpage["name"] . "" ;
			}
		}

		if ($rowpage["metadescription"])
		{
			$metadescription = $rowpage["metadescription"] . " | ".$prefs["prefCompanyName"]."" ;
		}
		else
		{
			$metadescription = $prefs['prefDefaultMetaDescription'] . "" ;
		}

		if ($rowpage["metakeywords"])
		{
			$metakeywords = $rowpage["metakeywords"] . "" ;
		}
		else
		{
			$metakeywords = $prefs['prefDefaultMetaKeywords'] . "" ;
		}



/*BESPOKE TITLE TAGGS - i.e. BLOG */

	//Shop product listing all Page
	if ($segs[0] == 'horses' ) {
		$selecttitle = "SELECT `name`,`metatitle`,`metadescription`,`metakeywords` FROM `sections` WHERE `id` = '" . $segs[1] . "' AND `showonweb` = 'Yes' ";
		$querytitle = mysqli_query($conn,$selecttitle);
		$rowtitle = mysqli_fetch_assoc($querytitle) ;
		
		 $titletag = "" . $rowtitle["metatitle"] . " - " . getCompanyName($prefs) . "" ;
		 if($rowtitle["metadescription"]) {$metadata = $rowtitle["metadescription"]." |";}else{$metadata = '';}
		 if($rowtitle["metakeywords"]) {$rowtitle["metakeywords"];} else {$metadata = '';}
		 

		}

	//Product Page
	if ($segs[0] == 'horse') {
		$selecttitle = "SELECT `name` FROM `products` WHERE `id` = '" . $segs[1] . "' AND `archived` = '0' ";
		$querytitle = mysqli_query($conn,$selecttitle);
		$rowtitle = mysqli_fetch_assoc($querytitle) ;
		
		 $titletag = "" . $rowtitle["name"] . "  from " . getCompanyName($prefs) . "" ;
		 if($rowtitle["metadata"]) {$metadata = $rowtitle["metadata"]." |";}else{$metadata = '';}
		 $metadescription = "" . $rowtitle["name"] . " | " .$metadata." " . $rowpage["metadescription"] . " from " . getCompanyName($prefs) . "" ;
		 $metakeywords = "" . getCompanyName($prefs) . ", " . $rowtitle["keywords"] . ", " . $rowtitle["name"] . ", " . $rowpage["metakeywords"] . "";

		}

	//Service Page
	if ($segs[0] == 'sectors-overview') {
		$selecttitle = "SELECT `name` FROM `services` WHERE `id` = '" . $segs[1] . "' AND `showonweb` = 'Yes' ";
		$querytitle = mysqli_query($conn,$selecttitle);
		$rowtitle = mysqli_fetch_assoc($querytitle) ;
		
		 $titletag = "" . $rowtitle["name"] . "" ;
		 $metadescription = "" . $rowtitle["name"] . " | " . $rowtitle["metadata"] . " | " . $rowpage["metadescription"] . " from " . getCompanyName($prefs) . "" ;
		 $metakeywords = "" . getCompanyName($prefs) . ", " . $rowtitle["name"] . ", " . $rowpage["metakeywords"] . "";
		
		}


	$titletag = preg_replace('/<[^>]+>/', ' ', $titletag);
	$titletag = preg_replace('/\s+/', ' ', trim($titletag));
	echo "<title>" . $titletag. "</title>" ;

	$metadescription = preg_replace('/<[^>]+>/', ' ', $metadescription);
	$metadescription = preg_replace('/\s+/', ' ', trim($metadescription));
	echo "<meta name='description' content='" . strip_tags($metadescription) . "'>" ;

	$metakeywords = preg_replace('/<[^>]+>/', ' ', $metakeywords);
	$metakeywords = preg_replace('/\s+/', ' ', trim($metakeywords));
	echo "<meta name='keywords' content='" . strip_tags($metakeywords) . "'>" ;

?>

	<!-- Meta Tags -->
    <meta name="language" content="en-uk" >
    <meta name="robots" content="ALL">
    <meta name="revisit-after" content="30">
    <meta name="author" content="<?php echo getCompanyName($prefs) ; ?>">
    <meta name="owner" content="<?php echo getCompanyName($prefs) .", ". getEmail($prefs) ; ?>">
    <meta name="copyright" content="<?php echo getCompanyName($prefs) ; ?>">
    <meta name="designer"  content="<?php echo $prefs['prefCopyrightText'] . " | ". $prefs['prefCopyrightLink'] ; ?>">
    <meta name="platform" content="<?php echo $prefs['prefMetaPlatformText'] ; ?>">
    <meta name="url" content="<?php echo $baseURL ; ?>">
	<meta name="coverage" content="Worldwide">
    <meta name="classification" content="Equine Breeding">
    <meta name="document-rights" content="Copyrighted Work">
    <meta name="document-rating" content="Safe for Kids">
    <meta name="document-type" content="Public">
    <meta name="document-class" content="Completed">
    <meta name="document-distribution" content="Global">

<?php
	if ($prefs['prefSiteSearchOn'] == 'No') {
    	echo "<meta name='robots' content='noindex'>" ;
	}
	else
	{
		if ($rowpage["pagesearch"] == 'No') {
			echo "<meta name='robots' content='noindex'>" ;
		}
		else
		{
			echo "<meta name='robots' content='index,follow,snippet,archive'>" ;
		}
	}
?>


<!-- END metadata -->    
