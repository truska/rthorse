<!-- START  recordImageMigration.php-->
<?php
error_reporting(1);

include('setting/main-top-files.php'); // Added by salva TDR | 16.03.2023


// --- Phase 2: Migrate horse images into gallery ---

$sql = "SELECT id, title, image FROM horses WHERE image <> ''";
$res = mysqli_query($conn, $sql);

$count = 0;

while ($row = mysqli_fetch_assoc($res)) {

    // --- Create slug (same logic as before) ---
    $inputname = $row['title'];
    $slug1 = $inputname;
    $pronameV = substr($slug1, 0, 100);
    $replaceMap = [".", ",", "/", "\\", "'", "*", "?", "@", "&", "%", "(", ")", "[", "]", "=", "-", " "];
    $pronameV = str_replace($replaceMap, "_", $pronameV);
    $pronameV = preg_replace('/_+/', '_', $pronameV);
    $slug = strtolower($pronameV);

    // --- Fixed values for this gallery type ---
    $form_id = 2;
    $form_name = 'products';
    $folder_name = 'images/horses/';

    // --- Insert new gallery record ---
    $insert = $conn->prepare("
        INSERT INTO gallery
        (record_id, form_id, form_name, name, image, folder_name, slug)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->bind_param("iisssss",
        $row['id'],
        $form_id,
        $form_name,
        $row['title'],
        $row['image'],
        $folder_name,
        $slug
    );

    if ($insert->execute()) {
        $count++;
    }
}

echo "✅ Gallery migration complete. Imported {$count} records.";
?>



<!-- END recordCopyv4 -->