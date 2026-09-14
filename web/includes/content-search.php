
<style>
    .search-box {
    max-width: 600px;
    margin: 0 auto;
    }
    .input-group-lg .form-control {
    font-size: 1.25rem;
    padding: 0.75rem 1rem;
    }
    .input-group-lg .btn {
    padding: 0.75rem 1rem;
    }

    .horse-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        background-color: #fff;
        transition: opacity 1s ease-in-out;
        opacity: 1;
    }
    .hover-img { position: relative; }
    .hover-img img:last-child {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0;
    }
    .hover-img:hover img:first-child { opacity: 0; }
    .hover-img:hover img:last-child { opacity: 1; }
</style>


<?php
// --- content-search.php ---
$table  = "products";
$formid = 2;
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchTerm = mysqli_real_escape_string($conn, $searchTerm);
?>

<div class="container py-4">
  <form class="search-box mb-4" method="get" action="/search">
    <div class="input-group input-group-lg">
      <input type="text" name="q" class="form-control"
             placeholder="Search horses..."
             value="<?php echo htmlspecialchars($searchTerm); ?>" required>
      <button class="btn btn-primary" type="submit">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </form>

<?php
if ($searchTerm !== '') {
    echo "<h2 class='text-center mb-4'>Search results for “" . htmlspecialchars($searchTerm) . "”</h2>";
    $sql = "SELECT * FROM `$table`
            WHERE `showonweb` = 'Yes'
              AND `archived` = 0
              AND `name` LIKE '%$searchTerm%'
            ORDER BY `name` ASC";
    $result = mysqli_query($conn, $sql);
    include('content-grid.php');
} else {
    echo "<h2 class='text-center mb-4'>Search our Horses</h2>";
    echo "<p class='text-center text-muted mb-5'>Enter a name or keyword above to find a horse.</p>";
}
?>
</div>
