<?php
// --- content-list.php ---
// Section or special listing page
$table  = "products";
$formid = 2;

$sectionId = isset($segs[1]) ? intval($segs[1]) : 0;
$isForSaleOnly = false;

// --- Detect if URL query or path indicates a "forsaleonly" view ---
if (isset($_GET['forsaleonly']) && strtolower($_GET['forsaleonly']) === 'yes') {
    $isForSaleOnly = true;
} elseif (isset($segs[1]) && strtolower($segs[1]) === 'forsaleonly') {
    $isForSaleOnly = true;
}

// --- Determine heading ---
$heading = '';
if ($isForSaleOnly) {
    $heading = "Horses For Sale";
} elseif ($sectionId > 0) {
    $sqlSection = "SELECT `name`, `nameoverride`
                   FROM `sections`
                   WHERE `id` = $sectionId
                   LIMIT 1";
    $resSection = mysqli_query($conn, $sqlSection);
    if ($resSection && mysqli_num_rows($resSection) > 0) {
        $rowSection = mysqli_fetch_assoc($resSection);
        $heading = !empty($rowSection['nameoverride'])
            ? trim($rowSection['nameoverride'])
            : trim($rowSection['name']);
    }
}

// --- Build SQL based on mode ---
if (isset($segs[2]) && strtolower($segs[2]) == 'horses-for-sale') {
    $sql = "SELECT *
    FROM `$table`
    WHERE `forsale` = 'Yes'
    AND `showonweb` = 'Yes'
    AND `archived` = 0
    ORDER BY `name` ASC";
}
else 
{


    if ($isForSaleOnly) {
        // ✅ Ignore section — show all horses for sale
        $sql = "SELECT * FROM `$table`
                WHERE `forsale` = 'Yes'
                AND `showonweb` = 'Yes'
                AND `archived` = 0
                ORDER BY `name` ASC";
    } 
    else 
    {
        // ✅ Normal section view
        $sql = "SELECT * FROM `$table`
                WHERE `section` = $sectionId
                AND `showonweb` = 'Yes'
                AND `archived` = 0
                ORDER BY (forsale='Yes') DESC, `name` ASC";
    }
}

$result = mysqli_query($conn, $sql);

echo "<div class='container py-4'>";
if ($heading !== '') {
    echo "<h2 class='mb-4 text-center'>" . htmlspecialchars($heading) . "</h2>";
}

// --- Shared grid display ---
include('content-grid.php');
echo "</div>";
?>
