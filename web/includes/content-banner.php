<?php
// Get Banners for page
$selectbanners = "SELECT * FROM `banner`
                  WHERE `page` = ".$slugID."
                  AND `showonweb` = 'Yes'
				  AND `archived` = 0 
                  ORDER BY `sort`";

// By Section - id section set against banner it will use this code
if($slugID = 5 AND $segs[1] > 0 ){
$selectbanners = "SELECT * FROM `banner`
                  WHERE `page` = ".$slugID."
                  AND `section` = ".$segs[1]."
                  AND `showonweb` = 'Yes'
				  AND `archived` = 0 
                  ORDER BY `sort`";
}

$querybanners = mysqli_query($conn,$selectbanners);
$banners = [];
while ($row = mysqli_fetch_assoc($querybanners)) {
    $banners[] = $row;
}

$num_banners = count($banners);

if ($num_banners > 1) {
?>
<!-- Carousel Version -->
<div id="carouselExampleCaptions" class="carousel slide parent-element" data-bs-ride="carousel">
    <?php
        showEditButton(8, $row["id"]);
    ?>
  <!-- Indicators -->
  <div class="carousel-indicators">
    <?php
    foreach ($banners as $i => $b) {
        $active = ($i == 0) ? 'active' : '';
        echo "<button type='button' data-bs-target='#carouselExampleCaptions'
                     data-bs-slide-to='{$i}' class='{$active}'
                     aria-label='Slide ".($i+1)."'></button>";
    }
    ?>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">
    <?php
    foreach ($banners as $i => $b) {
        $active = ($i == 0) ? 'active' : '';
        $imageBase = "/filestore/images/banners/lg/" . pathinfo($b["image"], PATHINFO_FILENAME);
        $webpPath = $_SERVER['DOCUMENT_ROOT'] . $imageBase . ".webp";
        $imageExt = file_exists($webpPath)
            ? ".webp"
            : "." . pathinfo($b["image"], PATHINFO_EXTENSION);
        $bgimageURL = $baseURL . $imageBase . $imageExt;

        echo "<div class='carousel-item {$active}'>";
			echo "<img src='{$bgimageURL}' class='d-block w-100' alt='".htmlspecialchars($b["title"])."'>";
			if ($b["title"] || $b["text"]) {
				echo "<div class='carousel-caption d-none d-md-block'>";
					if ($b["title"]) echo "<h2>".htmlspecialchars($b["title"])."</h2>";
					if ($b["title"]) echo "<h4>".htmlspecialchars($b["subtitle"])."</h4>";
					if ($b["text"]) echo "<p>".htmlspecialchars($b["text"])."</p>";
				echo "</div>";
			}
        echo "</div>";
    }
    ?>
  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button"
          data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button"
          data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
  </button>
</div>
<?php
} else {
    // --- Single banner fallback ---
    $b = $banners[0] ?? null;
    if ($b) {
        
        $bannerId = $b['id'] ?? null; // Extract banner ID from the record for the EDIT button

        $imageBase = "/filestore/images/banners/md/" . pathinfo($b["image"], PATHINFO_FILENAME);
        $webpPath = $_SERVER['DOCUMENT_ROOT'] . $imageBase . ".webp";
        $imageExt = file_exists($webpPath)
            ? ".webp"
            : "." . pathinfo($b["image"], PATHINFO_EXTENSION);
        $bgimageURL = $baseURL . $imageBase . $imageExt;
        ?>
        <div class="newsbanner  parent-element" style="background-image:url('<?php echo $bgimageURL; ?>') ; background-size:cover; background-repeat:no-repeat;">
            <?php
                showEditButton(8, $bannerId);
            ?>
            <div class="container text-center text-white py-5">
                <h1><?php echo htmlspecialchars($b["title"]); ?></h1>
                <h3 class="pt-2"><?php echo htmlspecialchars($b["subtitle"]); ?></h3>
                <h4 class="pt-2 pb-2"><?php echo htmlspecialchars($b["text"]); ?></h4>
            </div>
        </div>
        <?php
    }
}
?>
