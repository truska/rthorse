<!-- START header-code RXS -->

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

<!--Style Sheets -->
<?php 
$minified = 'No' ;
if($minified == 'Yes') {
	?>
	<!-- Minified -->
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/custom.min.css">
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/menu.min.css">
	<?php
}
else
{
	?>
	<!-- Original -->
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/custom.css">
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/menu.css">
	<?php
}
?>

<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $baseURL ; ?>/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $baseURL ; ?>/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $baseURL ; ?>/favicon-16x16.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;200;300;400;700&display=swap" rel="stylesheet">

<script src="https://kit.fontawesome.com/<?php echo $prefs["prefFontAwsomeToken"];?>" crossorigin="anonymous"></script>

<!-- Graphic Tools -->
<?php 
if($minified == 'Yes') {
	?> 
		<link rel="stylesheet" type="text/css" href="<?php echo $baseURL ; ?>/css/magicslideshow.min.css">
		<script src="<?php echo $baseURL ; ?>/js/magicslideshow.min.js"></script>
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/magicscroll.min.css">
		<script src="<?php echo $baseURL ; ?>/js/magicscroll.min.js"></script>
	<?php
}
else
{
	?>
		<!--
			<link rel="stylesheet" type="text/css" href="<?php echo $baseURL ; ?>/css/magicslideshow.css">
			<script src="<?php echo $baseURL ; ?>/js/magicslideshow.js"></script>
			<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/magicscroll.css">
			<script src="<?php echo $baseURL ; ?>/js/magicscroll.js"></script>`
		-->
		<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/magiczoomplus.css">
		<script src="<?php echo $baseURL ; ?>/js/magiczoomplus.js"></script>
	<?php
}
?>

<!-- <link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/lightbox.min.css"> -->

<?php
if ($prefs["prefCookieCheck"] == 'Yes') { ;?>
	<link rel="stylesheet" href="<?php echo $baseURL ; ?>/css/cookiealert.css" media="all">
<?php } ;?>

	<?php
        //echo $rowpage["headercode"] ; //in headerscripts 0/5/25
        include("custom-css.php"); 
        include("metadata.php"); 
        include("include-canonical.php");         
		include("includes/header-scripts.php"); 
		include("header-schema.php");
	
		if ($prefs['prefGoogleAnalyticsOn'] == 'Yes') {
			include("includes/google.php"); 
		}
?>

<?php

// Include Functions
?>
<!-- END header-code -->
