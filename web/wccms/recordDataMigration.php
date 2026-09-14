<!-- START recordDataMigration.php -->
<?php
error_reporting(1);

include('setting/main-top-files.php'); // Added by salva TDR | 16.03.2023




// Fetch old horse records
$sql = "SELECT * FROM horses";
$res = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($res)) {

    // --- Map section based on page ---
    switch ($row['page']) {
        case 3: $section = 1; break;
        case 4: $section = 2; break;
        case 9: $section = 3; break;
        case 11: $section = 4; break;
        default: $section = 0;
    }

    // --- Create slug using your sanitiser ---
    $inputname = $row['title'];
    $slug1 = $inputname;
    $pronameV = substr($slug1, 0, 100);
    $replaceMap = ["."," ,","/","\\","'","*","?","@","&","%","(",")","[","]","=","-"," "];
    $pronameV = str_replace($replaceMap, "_", $pronameV);
    $pronameV = preg_replace('/_+/', '_', $pronameV);
    $slug = strtolower($pronameV);

    // --- Insert into new products table ---
    $insert = $conn->prepare("
        INSERT INTO products
        (id, name, ref, sort, text, text1, sex, sire, dam, sirename, gsirename, ggsirename, image, showonweb, slug, section)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->bind_param("ississsisssssssi",
        $row['id'],
        $row['title'],
        $row['page'],
        $row['order'],
        $row['text'],
        $row['text2'],
        $row['sex'],
        $row['sireid'],
        $row['damid'],
        $row['sire'],
        $row['gsire'],
        $row['ggsire'],
        $row['image'],
        $row['showonweb'],
        $slug,
        $section
    );

    $insert->execute();
}

echo "✅ Migration complete.";
?>
<!-- END recordCopyv4 -->