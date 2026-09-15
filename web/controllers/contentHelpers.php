<?php


/**
 * Get a valid web path for an image, checking that it exists.
 * 
 * Priority:
 *   1. Normal image folder (e.g. /filestore/images/content/lg/)
 *   2. Old images folder (/filestore/old_images/)
 *   3. Default 'no-image.jpg' in the same main folder
 *
 * @param string $image    Filename from database (e.g. "horse1.jpg")
 * @param string $folder   Subfolder inside /filestore/images/  (e.g. "content" or "horses")
 * @param string $size     Size folder (lg, md, sm, xs)
 * @return string          Web path (starting with /filestore/…)
 */
/**
 * Get a valid web path for an image, checking that it exists.
 * Falls back to old_images folder or same-folder no-image.jpg
 */
function getValidImagePath($image, $folder = 'content', $size = 'md') {
    $baseDir = __DIR__ . "/../filestore/images";
    $webPath = "/filestore/images";

    if (empty($image)) {
        // If no filename provided, use folder+size specific fallback
        return "$webPath/$folder/$size/no-image.jpg";
    }

    $primary = "$baseDir/$folder/$size/$image";
    $fallbackOld = "$baseDir/old_images/$image";
    $fallbackNo = "$baseDir/$folder/$size/no-image.jpg";

    if (file_exists($primary)) {
        return "$webPath/$folder/$size/$image";
    }
    if (file_exists($fallbackOld)) {
        return "$webPath/old_images/$image";
    }
    if (file_exists($fallbackNo)) {
        return "$webPath/$folder/$size/no-image.jpg";
    }

    // Absolute last resort (if folder missing entirely)
    return "$webPath/$folder/$size/no-image.jpg";
}



// --- Render gallery for a content block ---
function renderGallery($conn, $formid, $recordid, $baseURL, $sort = "id ASC", $folder = "content", $productName = "", bool $showFallback = true) {
    // --- Get gallery records ---
    $selectgallery = "
        SELECT * FROM `gallery`
        WHERE `form_id` = '$formid' 
        AND `record_id` = '$recordid'
        AND `showonweb` = 'Yes' 
        AND `archived` = 0
        ORDER BY $sort
    ";
    $querygallery = mysqli_query($conn, $selectgallery);
    $numgallery = mysqli_num_rows($querygallery);

    // --- No images found: show fallback ---
    if ($numgallery < 1) {
        if (!$showFallback) {
            return;
        }

        $fallbackMd = "/filestore/images/{$folder}/md/no-image.jpg";
        $fallbackLg = "/filestore/images/{$folder}/lg/no-image.jpg";
        echo "<div class='text-center'>
                <a class='MagicZoom' id='zoom-$recordid' title='No image available' data-options='zoomPosition: inner'
                   href='{$baseURL}{$fallbackLg}'>
                   <img src='{$baseURL}{$fallbackMd}' class='img-fluid' alt='No image available'>
                </a>
              </div>";
        return;
    }

    // --- Get first image as main ---
    $rowgallerymain = mysqli_fetch_assoc($querygallery);
    mysqli_data_seek($querygallery, 0); // Reset pointer

    // ✅ Use helper to get verified image paths
    $mainImage = !empty($rowgallerymain['image']) ? $rowgallerymain['image'] : '';
    $mainImgLg = getValidImagePath($mainImage, $folder, 'lg');
    $mainImgMd = getValidImagePath($mainImage, $folder, 'md');


    $defaultText = htmlspecialchars($productName, ENT_QUOTES);

    $mainAlt = !empty(trim($rowgallerymain['alttag'] ?? ''))
        ? htmlspecialchars(trim($rowgallerymain['alttag']), ENT_QUOTES)
        : $defaultText;

    $mainCaption = !empty(trim($rowgallerymain['caption'] ?? ''))
        ? htmlspecialchars(trim($rowgallerymain['caption']), ENT_QUOTES)
        : $defaultText;

    // --- Output main image ---
    echo "<a class='MagicZoom' id='zoom-$recordid' title='{$mainCaption}' data-options='zoomPosition: inner' href='{$baseURL}{$mainImgLg}'>
             <img src='{$baseURL}{$mainImgMd}' class='img-fluid' alt='{$mainAlt}'>
          </a>";

    // A single image needs no thumbnail of itself. Keep the gallery layout
    // clean, while retaining thumbnails when visitors can switch images.
    if ($numgallery > 1) {
        echo "<div class='row gx-3 gy-3 pt-3'>";
        while ($rowgallery = mysqli_fetch_assoc($querygallery)) {
            $image = !empty($rowgallery['image']) ? $rowgallery['image'] : '';

            // Use verified paths for both large and medium images.
            $imgLg = getValidImagePath($image, $folder, 'lg');
            $imgMd = getValidImagePath($image, $folder, 'md');

            $thumbAlt = !empty(trim($rowgallery['alttag'] ?? ''))
                ? htmlspecialchars(trim($rowgallery['alttag']), ENT_QUOTES)
                : $defaultText;

            $thumbCaption = !empty(trim($rowgallery['caption'] ?? ''))
                ? htmlspecialchars(trim($rowgallery['caption']), ENT_QUOTES)
                : $defaultText;

            echo "<div class='col-6 col-md-3 col-lg-3'>";
            echo "<a data-zoom-id='zoom-$recordid'
                 href='{$baseURL}{$imgLg}'
                 data-image='{$baseURL}{$imgMd}'
                 title='{$thumbCaption}'>";
            echo "<img src='{$baseURL}{$imgMd}' class='img-fluid w-100' alt='{$thumbAlt}'>";
            echo "</a>";
            echo "</div>";
        }
        echo "</div>";
    }
}



/* works on standard without folder image variable
    function renderGallery($conn, $formid, $recordid, $baseURL, $sort = "id ASC") {
        $selectgallery = "
            SELECT * FROM `gallery`
            WHERE `form_id` = '$formid' 
            AND `record_id` = '$recordid'
            AND `showonweb` = 'Yes' 
            AND `archived` = 0
            ORDER BY $sort
        ";
        $querygallery = mysqli_query($conn, $selectgallery);
        $numgallery = mysqli_num_rows($querygallery);
        if ($numgallery < 1) return; // No gallery, nothing to render

        // Get first image as main
        $rowgallerymain = mysqli_fetch_assoc($querygallery);
        mysqli_data_seek($querygallery, 0); // Reset pointer

        echo "<a class='MagicZoom' id='zoom-$recordid' title='...' 
                href='{$baseURL}/filestore/images/content/lg/{$rowgallerymain['image']}'>
                <img src='{$baseURL}/filestore/images/content/md/{$rowgallerymain['image']}' class='img-fluid'>
            </a>";

        echo "<div class='row gx-3 gy-3 pt-3'>";
        while ($rowgallery = mysqli_fetch_assoc($querygallery)) {
            echo "<div class='col-6 col-md-3 col-lg-4'>";
            echo "<a data-zoom-id='zoom-$recordid' 
                    href='{$baseURL}/filestore/images/content/lg/{$rowgallery['image']}' 
                    data-image='{$baseURL}/filestore/images/content/md/{$rowgallery['image']}' 
                    title='...'>";
            echo "<img src='{$baseURL}/filestore/images/content/md/{$rowgallery['image']}' class='img-fluid w-100'></a>";
            echo "</div>";
        }
        echo "</div>";
    }
*/

// --- Render one column (handles subheading, image, text, and optional gallery) ---
function renderContentColumn($rowcontent, $colIndex, $baseURL, $conn, $formid, $sort, $includeGallery = false) {
    $subheading = $rowcontent["subheading" . ($colIndex ?: "")] ?? '';
    $image      = $rowcontent["image" . ($colIndex ?: "")] ?? '';
    $text       = $rowcontent["text" . ($colIndex ?: "")] ?? '';

    if (!$subheading && !$image && !$text) return;

    echo "<div class='content-col col'>";
    if ($subheading) echo "<h3>{$subheading}</h3>";
    if ($image) echo "<img src='{$baseURL}/filestore/images/content/lg/{$image}' class='img-fluid mb-3' alt=''>";
    if ($text) echo $text;
    // Content blocks can have a normal image as well as an optional gallery.
    // Do not add a placeholder beneath the normal image when no gallery exists.
    if ($includeGallery) renderGallery($conn, $formid, $rowcontent["id"], $baseURL, $sort, 'content', '', false);
    echo "</div>";
}



function getHorse($conn, $baseURL, $id, $level = 1, $maxGen = 4) {
    // If no ID or beyond allowed generations, return placeholder
    if (empty($id) || $id == 0 || $level > $maxGen) {
        return [
            'id'   => 0,
            'name' => 'To be added',
            'link' => '',
            'sire' => 0,
            'dam'  => 0,
            'slug' => ''
        ];
    }

    $sql = "SELECT id, name, sire, dam, slug 
            FROM products 
            WHERE id = $id 
              AND showonweb = 'Yes' 
              AND archived = 0 
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if (!$res || mysqli_num_rows($res) == 0) {
        // No record found → same placeholder
        return [
            'id'   => 0,
            'name' => 'To be added',
            'link' => '',
            'sire' => 0,
            'dam'  => 0,
            'slug' => ''
        ];
    }

    $row  = mysqli_fetch_assoc($res);
    $slug = !empty($row['slug']) ? $row['slug'] : strtolower(str_replace(' ', '-', $row['name']));
    $link = $baseURL . '/horse/' . $row['id'] . '/' . $slug;

    return [
        'id'   => $row['id'],
        'name' => htmlspecialchars($row['name']),
        'link' => $link,
        'sire' => $row['sire'],
        'dam'  => $row['dam'],
        'slug' => $slug
    ];
}

function renderHorseName($horse) {
    return !empty($horse['link'])
        ? "<a href='{$horse['link']}'>{$horse['name']}</a>"
        : $horse['name'];
}

/**
 * Return the first horse image (xs size) or a folder-specific no-image fallback.
 * Uses getValidImagePath() to check existence and select best available image.
 */
/**
 * Return horse image (xs size) or folder-specific no-image fallback.
 * Optional $maxHeight parameter for specific generations (e.g. generation 4).
 */

function getHorseImage($conn, $baseURL, $recordid, $maxHeight = null) {
    $folder = 'horses';
    $imgFile = '';

    if (!empty($recordid) && $recordid > 0) {
        $sql = "SELECT image, folder_name
                FROM gallery
                WHERE form_id = 2
                  AND record_id = $recordid
                  AND showonweb = 'Yes'
                  AND archived = 0
                ORDER BY sort ASC, id ASC
                LIMIT 1";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $folder = !empty($row['folder_name']) ? $row['folder_name'] : $folder;
            $imgFile = $row['image'];
        }
    }

    // Fallback if nothing found or record empty
    if (empty($imgFile)) {
        $imgFile = 'no-image.jpg';
    }

    // Build image URL
    $imgSrc = "{$baseURL}/filestore/images/{$folder}/xs/{$imgFile}";

    // Return final markup
    return "<div class='pedigree-thumb mt-2'>
                <img src='{$imgSrc}' alt='' 
                     class='img-fluid rounded shadow-sm'
                     style='max-width:150px;" .
                     ($maxHeight ? "max-height:{$maxHeight}px;" : "") .
                     "object-fit:cover; object-position:center;'>
            </div>";
}


/**
 * Quick pedigree names for listing cards.
 * Uses products.id, products.sire, products.dam (all IDs), and products.name (horse name).
 * For the pedigree on the lists page Dam, Dam's Sire and Dam's Dam's Sire
 * *
 * Returns:
 *  - sire_name
 *  - dams_sire_name
 *  - dams_dams_sire_name
 */
function getListingPedigreeNames(mysqli $conn, int $productId): array
{
    $productId = (int)$productId;

    $sql = "
        SELECT
            s.name   AS sire_name,
            ds.name  AS dams_sire_name,
            dds.name AS dams_dams_sire_name
        FROM products p
        LEFT JOIN products s   ON s.id   = p.sire
        LEFT JOIN products d   ON d.id   = p.dam
        LEFT JOIN products ds  ON ds.id  = d.sire
        LEFT JOIN products dd  ON dd.id  = d.dam
        LEFT JOIN products dds ON dds.id = dd.sire
        WHERE p.id = $productId
        LIMIT 1
    ";

    $out = [
        'sire_name' => '',
        'dams_sire_name' => '',
        'dams_dams_sire_name' => '',
    ];

    if ($res = mysqli_query($conn, $sql)) {
        if (mysqli_num_rows($res) === 1) {
            $row = mysqli_fetch_assoc($res);
            $out['sire_name'] = isset($row['sire_name'])
            ? trim(stripslashes($row['sire_name']))
            : '';
        
        $out['dams_sire_name'] = isset($row['dams_sire_name'])
            ? trim(stripslashes($row['dams_sire_name']))
            : '';
        
        $out['dams_dams_sire_name'] = isset($row['dams_dams_sire_name'])
            ? trim(stripslashes($row['dams_dams_sire_name']))
            : '';
        
        }
        mysqli_free_result($res);
    }

    return $out;
}


?>
