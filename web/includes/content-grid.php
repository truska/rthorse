<?php
// --- content-grid.php ---
// Renders a product grid.
// Expects: $result (MySQLi result), $conn, $baseURL, $formid.
?>
    <style>
        /* Keep card boxes square and clean */
        .hover-img {
        overflow: hidden;
        background-color: #f8f9fa;
        position: relative;
        }

        /* Default image behaviour */
        .horse-image {
        object-fit: cover;        /* fill without distortion */
        object-position: center;  /* center both ways */
        width: 100%;
        height: 100%;
        transition: opacity 0.4s ease, transform 0.4s ease;
        }

        /* Hover image overlay */
        .horse-image.position-absolute {
        opacity: 0;
        }
        .hover-img:hover .horse-image.position-absolute {
        opacity: 1;
        }

        /* Slight zoom for subtle movement */
        .hover-img:hover .horse-image:not(.position-absolute) {
        transform: scale(1.03);
        }

        /* Grayscale version for single-image hover */
        .grayscale-hover {
        filter: grayscale(100%) brightness(80%);
        }
    </style>

<?php

if (!isset($result) || !$result || mysqli_num_rows($result) === 0) {
    echo "<p class='text-center text-muted'>No products found.</p>";
    return;
}

$count = mysqli_num_rows($result);
echo "<p class='text-center text-muted mb-3'>Records found: {$count}</p>";

echo "<div class='row g-4'>";

while ($row = mysqli_fetch_assoc($result)) {
    $productId   = $row['id'];
    $productName = htmlspecialchars($row['name']);
    $productNameOverride = htmlspecialchars($row['nameoverride']);
    $productText = htmlspecialchars($row['summarytext']);
    $productSlug = htmlspecialchars($row['slug']);
    $productForSale = htmlspecialchars($row['forsale']);
    $link        = $baseURL . "/horse/" . $productId . "/" . $productSlug;

        //Set Name
        if($productNameOverride = $row['nameoverride']){
            $productName = $row['nameoverride'] ;
        }


    if($productForSale == 'Yes') {
        $forsale = "<i class='fa-solid fa-gavel text-warning'></i>" ;
    }
    else
    {
        $forsale = '' ;
    }
    
    // Load up to 2 gallery images
    $imgsql = "SELECT * FROM `gallery`
                WHERE `form_id` = $formid
                AND `record_id` = $productId
                ORDER BY `sort` ASC
                LIMIT 2";
    $imgres = mysqli_query($conn, $imgsql);
    $images = [];
    if ($imgres && mysqli_num_rows($imgres) > 0) {
        while ($imgrow = mysqli_fetch_assoc($imgres)) {
            $images[] = $baseURL . "/filestore/" . $imgrow['folder_name'] . "md/" . $imgrow['image'];
        }
    }

    // Card
    echo "<div class='col-12 col-sm-6 col-md-4 col-lg-3'>";
    echo "<div class='card h-100 border-0 shadow-sm'>";
        echo "<a href='{$link}' class='text-decoration-none text-dark'>";
            echo "<div class='ratio ratio-1x1 position-relative hover-img bg-light d-flex align-items-center justify-content-center'>";

                // --- DEBUG: what images do we have ---
                echo "<!-- DEBUG images: " . print_r($images, true) . " -->";

                if (count($images) >= 1) {
                    $img1 = basename($images[0]);
                    $img1Path = getValidImagePath($img1, 'horses', 'md');
                    echo "<img src='{$baseURL}{$img1Path}' class='card-img-top img-fluid horse-image' alt='{$productName}'>";

                    if (isset($images[1]) && $images[1] !== $images[0]) {
                        // second real image for hover
                        $img2 = basename($images[1]);
                        $img2Path = getValidImagePath($img2, 'horses', 'md');
                        echo "<img src='{$baseURL}{$img2Path}' class='card-img-top img-fluid horse-image position-absolute top-0 start-0 w-100 h-100'>";
                    } else {
                        // only one image: use grayscale clone as hover effect
                        echo "<img src='{$baseURL}{$img1Path}' class='card-img-top img-fluid horse-image grayscale-hover position-absolute top-0 start-0 w-100 h-100'>";
                    }
                } else {
                    // fallback no image
                    $fallbackPath = getValidImagePath('', 'horses', 'md');
                    echo "<img src='{$baseURL}{$fallbackPath}' class='card-img-top img-fluid horse-image' alt='No image available'>";
                }

            echo "</div>";

            echo "<div class='card-body text-center'>";
                echo "<h5 class='card-title mb-2'>{$productName} {$forsale}</h5>";
                /*
                if (!empty($productText)) {
                    echo "<p class='card-text small text-muted'>{$productText}</p>";                    
                }
                */
                $ped = getListingPedigreeNames($conn, (int)$productId);
                    $sireName         = $ped['sire_name'];
                    $damsSireName     = $ped['dams_sire_name'];
                    $damsDamsSireName = $ped['dams_dams_sire_name'];
                echo "<span style='font-size:11px;'><em>".$sireName." | ".$damsSireName." |  ".$damsDamsSireName."</em></span>" ;
            echo "</div>";

        echo "</a>";
    echo "</div>";
echo "</div>";


}

echo "</div>"; // row
?>
