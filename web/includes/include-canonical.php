<!-- START include-canonical -->

<?php
//Chonicle Code 

$canonicaltext = $baseURL."".htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES) ;

if($segs[0] == 'welcome' ){
	$canonicaltext = $baseURL."" ; // ameded 20250313 - was $baseURL."/welcome"
}


if($segs[0] == 'news' AND $segs[1]) {
	$canonicaltext =  $baseURL."/news" ;
}

if($segs[0] == 'newsstory' AND $segs[1] ){	
	$selectcanonical = "SELECT `id`,`slug` FROM `news_publication` WHERE `id` = ".$segs[1];
	$querycanonical = mysqli_query($conn,$selectcanonical);
	$rowcanonical = mysqli_fetch_assoc($querycanonical) ;
	if($rowcanonical["slug"] == $segs[2]) { //if slug = url no cononical needed
		}
		else
		{
			$canonicaltext = $baseURL."/news-story/".$rowcanonical["id"]."/".$rowcanonical["slug"] ;
		}
}

if($segs[0] == 'people-profile' AND $segs[1] ){	
	$selectcanonical = "SELECT `id`,`firstname`,`surname` FROM `people` WHERE `id` = ".$segs[1];
	$querycanonical = mysqli_query($conn,$selectcanonical);
	$rowcanonical = mysqli_fetch_assoc($querycanonical) ;
	
	$canonicaltext = $baseURL."/people-profile/".$rowcanonical["id"]."/".strtolower($rowcanonical["firstname"])."-".strtolower($rowcanonical["surname"]) ;

	if($rowcanonical["slug"] == $segs[2]) { //if slug = url no cononical needed
		}
		else
		{
			$canonicaltext = $baseURL."/people-profile/".$rowcanonical["id"]."/".strtolower($rowcanonical["firstname"])."-".strtolower($rowcanonical["surname"]) ;
		}
}

if($segs[0] == 'news-story' AND $segs[2] == ''){
	//get artict details based on  $segs[1] id;
	$selectcanonical = "SELECT `id`,`slug` 	FROM `news` WHERE `id` = ".$segs[1]." ";
	$querycanonical = mysqli_query($conn,$selectcanonical);
	$rowcanonical = mysqli_fetch_assoc($querycanonical) ;

	$canonicaltext = $baseURL."/news-story/".$rowcanonical["id"]."/".$rowcanonical["slug"] ;
}
	
echo "<link rel='canonical' href='".$canonicaltext."' >" ;

?>

<!-- END include-canonical -->