<?php
  // =====================================
  // content-detail
  // =====================================

  // Ensure $segs[1] is set and numeric
  $productId = isset($segs[1]) ? intval($segs[1]) : 0;

  if ($productId <= 0) {
      echo "<div class='alert alert-warning'>Invalid product reference.</div>";
      return;
  }


  $allowedIPs = [
    $prefs['prefTruskaIP'],
    $prefs['prefCoderIP'],
    $prefs['prefClientIP'],
    $prefs['prefClient1IP']
];

?>

<style>
  /* --- Pedigree Table Styling --- */
  .pedigree-table a {
    text-decoration: none;
    color: #444;
    transition: color 0.2s ease;
  }
  .pedigree-table a:hover {
    color: #222;
  }

  .pedigree-table td {
    min-width: 160px;
    vertical-align: top;
    text-align: center;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  /* --- Pedigree Generation 4 layout --- */
  /* --- Pedigree layout alignment --- */

  /* Default cell look for generations 1–3 */
  .pedigree-table td {
    vertical-align: top;
    text-align: center;          /* centre text + images in earlier gens */
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  /* Generation 4 container (image left, name right) */
  .pedigree-item {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 0.6rem;
    text-align: left;
  }

  /* Common image styling */
  .pedigree-thumb img {
    display: block;
    border-radius: 6px;
    object-fit: cover;
    max-width: 70px;
    height: auto;
    margin: 0 auto;
  }

  .pedigree-thumb:empty {
    display: none;
  }

  /* Name and relationship label */
  .pedigree-name a {
    color: #444;
    text-decoration: none;
    line-height: 1.3;
  }

  .pedigree-name a:hover {
    color: #222;
  }

  .pedigree-name small {
    display: block;
    font-size: 0.8rem;
    color: #777;
    margin-top: 0.2rem;
  }

  /* Slightly larger images for higher generations (optional) */
  .pedigree-table td[rowspan="8"] .pedigree-thumb img { max-width: 120px; }
  .pedigree-table td[rowspan="4"] .pedigree-thumb img { max-width: 100px; }
  .pedigree-table td[rowspan="2"] .pedigree-thumb img { max-width: 80px; }

  .siredam p {
    font-size: smaller;
  }

  @media (min-width: 992px) {
    .pedigree-thumb img { max-width: 100px; }
  }


    /* --- Custom Pedigree & More Info Buttons --- */
    .custom-btn {
      width: 75%;
      text-align: left;
      color: #555;                 /* softer dark grey text */
      border-color: #999;          /* mid-grey border */
      background-color: #f9f9f9;   /* subtle off-white bg */
      transition: all 0.2s ease-in-out;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .custom-btn i {
      color: #666;
    }
    .custom-btn:hover,
    .custom-btn:focus {
      background-color: #e0e0e0;   /* light grey shade */
      border-color: #888;
      color: #222;
      box-shadow: 0 2px 5px rgba(0,0,0,0.15);
      text-decoration: none;
    }

    @media (max-width: 768px) {
      .custom-btn {
        width: 100%;               /* full width on mobile for balance */
        text-align: center;
      }
  }
</style>


<?php

  // Get Data
    $sql = "SELECT * FROM products 
            WHERE id = $productId 
            AND archived = 0 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<div class='alert alert-info'>No Horse found.</div>";
        return;
    }
    $rowproduct = mysqli_fetch_assoc($result);
  // End Get Data

  // SET Pedigree etc
    if($rowproduct['forsale'] == 'Yes') {
      $forsale = "<i class='fa-solid fa-gavel text-warning'></i>" ;
    }
      //Set Name
      $name = $rowproduct['name'] ;
      if($rowproduct['nameoverride']){
        $name = $rowproduct['nameoverride'] ;
      }

      $imagetitledefault = $name ;

    // Lookup sire and dam if present
    $sireName = $damName = '';
    $sireLink = $damLink = '#';

    if (!empty($rowproduct['sire'])) {
        $sireId = intval($rowproduct['sire']);
        $sqlSire = "SELECT name, slug FROM products WHERE id = ".$rowproduct['sire']." AND archived = 0 LIMIT 1" ;
        //error_log("site sql: ".$sqlSire) ;
        $resSire = mysqli_query($conn, $sqlSire);
        if ($resSire && mysqli_num_rows($resSire) > 0) {
            $rowSire = mysqli_fetch_assoc($resSire);
            $sireName = htmlspecialchars($rowSire['name']);          
            $sireSlug = htmlspecialchars($rowSire['slug']);
            $sireLink = $baseURL."/horse/".$sireId."/".$sireSlug;
        }
    }

    if (!empty($rowproduct['dam'])) {
        $damId = intval($rowproduct['dam']);
        $sqlDam = "SELECT name, slug FROM products WHERE id = $damId AND archived = 0 LIMIT 1";
        $resDam = mysqli_query($conn, $sqlDam);
        if ($resDam && mysqli_num_rows($resDam) > 0) {
            $rowDam = mysqli_fetch_assoc($resDam);
            $damName = htmlspecialchars($rowDam['name']);
            $damSlug = htmlspecialchars($rowDam['slug']);
            $damLink = $baseURL."/horse/".$damId."/".$damSlug;
        }
    }
  // END Set Pedigree etc
?>

<!-- =====================================
     START: Product (Horse) Detail Layout
     ===================================== -->
<div class="container my-4 parent-element">

  <?php
    showEditButton(2, $segs[1]);
  ?>

    <!-- Horse Name -->
    <div class="row mb-3">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-0"><?php echo stripslashes(htmlspecialchars($name))."&nbsp;&nbsp;".$forsale; ?></h1>
        </div>
    </div>

    <!-- Main Row -->
    <div class="row g-4 align-items-start">

        <!-- Left Column -->
        <div class="col-12 col-md-6">

        <!-- Product Text -->
        <?php if (!empty($rowproduct['text'])): ?>
            <div class="mb-4">
                <?php echo $rowproduct['text']; ?>
                <?php // echo $rowproduct['section']." | ".$rowproduct['ref']; ?>
            </div>
        <?php endif; ?>

            <?php
          if($rowproduct['section'] != 5 AND $rowproduct['section'] != 6){
            ?>

            <!-- Action Buttons -->
            <div class="row mb-4 g-2">
              <div class="col-6 d-flex justify-content-start">
                <button type="button" class="btn btn-outline-secondary custom-btn" data-bs-toggle="modal" data-bs-target="#pedigreeModal">
                  <i class="fa-solid fa-diagram-project me-1"></i> View Pedigree
                </button>
              </div>

              <div class="col-6 d-flex justify-content-start">
                <?php if (!empty($rowproduct['text1'])): ?>
                  <button type="button" class="btn btn-outline-secondary custom-btn" data-bs-toggle="modal" data-bs-target="#moreInfoModal">
                    <i class="fa-solid fa-circle-info me-1"></i> More Info
                  </button>
                <?php endif; ?>
              </div>
            </div>

            <!-- Success Stories --> 
            <!-- Horse table field  -->
            <?php
              if($rowproduct["summarytext"]) {
                echo "<div class='mb-4'>" ;
                  echo "<h5 class='fw-bold mb-3'>Success Stories</h5>" ;

                  echo "<div class='col-12'>".$rowproduct["summarytext"]."</div>" ;
                echo "</div>" ;
              }
            ?>    
          
          
            <!-- Pedigree Details -->
            <div class="mb-4">

                <h5 class="fw-bold mb-3">Pedigree Details</h5>
                <div class="row g-2">
                    <div class="col-6">
                        <?php
                        $sireDisplay = "To be Added";
                        if (!empty($rowproduct['sire'])) {
                            $sireId = intval($rowproduct['sire']);
                            $sqlSire = "SELECT `id`,`name`,`slug`,`nameoverride`,`text` FROM products WHERE id = $sireId AND  archived = 0 LIMIT 1";
                            $resSire = mysqli_query($conn, $sqlSire);
                            if ($resSire && mysqli_num_rows($resSire) > 0) {
                                $rowSire = mysqli_fetch_assoc($resSire);
                                $sireDisplay = stripslashes(htmlspecialchars($rowSire['name']));
                                if($rowSire['nameoverride']) {
                                  $sireDisplay = htmlspecialchars($rowSire['nameoverride']);
                                } ;
                                $sireSlug = htmlspecialchars($rowSire['slug']);
                            }
                        }
                        if($sireId) {
                        echo "<strong>Sire:</strong> <a href='".$baseURL."/horse/".$sireId."/".$sireSlug."'>" . $sireDisplay."</a>" ;
                          //echo " [idd: ".$sireId."]";
                          if (in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
                            echo "<em> [id: ".$sireId."]</em>";
                          }
                        }
                        else{
                          echo "<strong>Sire:</strong> " . $sireDisplay."" ;
                             //echo "[idx: ".$sireId."]" ;
                             if (in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
                              echo "<em> [id: ".$sireId."]</em>";
                            }
                        }
                        echo "<hr><span class='siredam'>".$rowSire['text']."</span>" ;
                        ?>
                    </div>

                    <div class="col-6">
                        <?php
                        $damDisplay = "To be Added";
                        if (!empty($rowproduct['dam'])) {
                            $damId = intval($rowproduct['dam']);
                            $sqlDam = "SELECT `id`,`name`,`slug`,`nameoverride`,`text` FROM products WHERE id = $damId AND archived = 0 LIMIT 1";
                            $resDam = mysqli_query($conn, $sqlDam);
                            if ($resDam && mysqli_num_rows($resDam) > 0) {
                                $rowDam = mysqli_fetch_assoc($resDam);
                                $damDisplay = stripslashes(htmlspecialchars($rowDam['name']));
                                if($rowDam['nameoverride']) {
                                  $damDisplay = htmlspecialchars($rowDam['nameoverride']);
                                } ;
                                $damSlug = htmlspecialchars($rowDam['slug']);
                            }
                        }
                        if($damId) {
                          echo "<strong>Dam:</strong> <a href='".$baseURL."/horse/".$damId."/".$damSlug."'>" . $damDisplay."</a>" ;

                          
                    
                            if (in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
                              echo "<em> [id: ".$damId."]</em>";
                            }



                            
                          }
                          else{
                            echo "<strong>Dam:</strong> " . $damDisplay." [id: ".$damId."]";
                          }
                        echo "<hr><span class='siredam'>".$rowDam['text']."</span>" ;
                        ?>
                    </div>
                </div>
                
            </div>
            <?php
          }
            
            
            
            ?>

        </div>

    <!-- Right Column: Gallery -->
    <div class="col-12 col-md-6">
        <?php
        // Render gallery for this horse (formid = 2)
        // Using your shared function definition
        if (function_exists('renderGallery')) {
          $name = str_replace(', Select', '', $name);
          $name = trim($name);
      
            renderGallery($conn, 2, $productId, $baseURL, "sort ASC" , "horses" , $name ) ;
        } else {
            echo "<div class='text-muted fst-italic'>Gallery function not found.</div>";
        }
        ?>
    </div>



    </div>
</div>


<!-- Pedigree Modal -->
<div class="modal fade" id="pedigreeModal" tabindex="-1" aria-labelledby="pedigreeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pedigreeModalLabel">
          Pedigree for <?php echo htmlspecialchars($rowproduct['name']); ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <?php
          //----------------------------------------------------
          // Helper: Get horse basic info
          //----------------------------------------------------


            //----------------------------------------------------
          // Build Generations
            //----------------------------------------------------

            // Generation 1 (Horse)
            $productSlug = !empty($rowproduct['slug'])
                ? $rowproduct['slug']
                : strtolower(str_replace(' ', '-', $rowproduct['name']));

            $horse = [
                'id'   => $rowproduct['id'],
                'name' => stripslashes(htmlspecialchars($rowproduct['name'])),
                'link' => $baseURL . '/horse/' . $rowproduct['id'] . '/' . $productSlug,
                'sire' => $rowproduct['sire'],
                'dam'  => $rowproduct['dam'],
                'slug' => $productSlug
            ];

            // Generation 2
            $sire1 = getHorse($conn, $baseURL, $horse['sire']);
            $dam1  = getHorse($conn, $baseURL, $horse['dam']);

            // Generation 3
            $sire2sire = getHorse($conn, $baseURL, $sire1['sire']);
            $sire2dam  = getHorse($conn, $baseURL, $sire1['dam']);
            $dam2sire  = getHorse($conn, $baseURL, $dam1['sire']);
            $dam2dam   = getHorse($conn, $baseURL, $dam1['dam']);

            // Generation 4
            $sire3sire  = getHorse($conn, $baseURL, $sire2sire['sire']);
            $sire3dam   = getHorse($conn, $baseURL, $sire2sire['dam']);
            $sire3sire2 = getHorse($conn, $baseURL, $sire2dam['sire']);
            $sire3dam2  = getHorse($conn, $baseURL, $sire2dam['dam']);
            $dam3sire   = getHorse($conn, $baseURL, $dam2sire['sire']);
            $dam3dam    = getHorse($conn, $baseURL, $dam2sire['dam']);
            $dam3sire2  = getHorse($conn, $baseURL, $dam2dam['sire']);
            $dam3dam2   = getHorse($conn, $baseURL, $dam2dam['dam']);

            //----------------------------------------------------
          // Build Generations
            //----------------------------------------------------
        ?>

        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle pedigree-table">
            <thead class="table-light">
              <tr>
                <th>Generation 1</th>
                <th>Generation 2</th>
                <th>Generation 3</th>
                <th>Generation 4</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <!-- Generation 1 -->
                <td rowspan="8">
                  <?php echo stripslashes(renderHorseName($horse)); ?>
                  <?php echo getHorseImage($conn, $baseURL, $horse['id']); ?>
                </td>

                <!-- Generation 2 -->
                <td rowspan="4">
                  <?php echo stripslashes(renderHorseName($sire1)); ?><br>
                  <small>(Sire)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $sire1['id']); ?>
                </td>

                <!-- Generation 3 -->
                <td rowspan="2">
                  <?php echo stripslashes(renderHorseName($sire2sire)); ?><br>
                  <small>(Sire’s Sire)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $sire2sire['id']); ?>
                </td>

                <!-- Generation 4 -->
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $sire3sire['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($sire3sire)); ?><br>
                      <small>(Sire’s Sire's Sire)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $sire3dam['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($sire3dam)); ?><br>
                      <small>(Sire’s Sire's Dam)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td rowspan="2">
                  <?php echo stripslashes(renderHorseName($sire2dam)); ?><br>
                  <small>(Sire’s Dam)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $sire2dam['id']); ?>
                </td>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $sire3sire2['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($sire3sire2)); ?><br>
                      <small>(Sire’s Dam’s Sire)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $sire3dam2['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($sire3dam2)); ?><br>
                      <small>(Sire’s Dam’s Dam)</small>
                    </div>
                  </div>
                </td>
              </tr>

              <!-- Dam side -->
              <tr>
                <td rowspan="4">
                  <?php echo stripslashes(renderHorseName($dam1)); ?><br>
                  <small>(Dam)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $dam1['id']); ?>
                </td>
                <td rowspan="2">
                  <?php echo stripslashes(renderHorseName($dam2sire)); ?><br>
                  <small>(Dam’s Sire)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $dam2sire['id']); ?>
                </td>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $dam3sire['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($dam3sire)); ?><br>
                      <small>(Dam’s Sire's Sire)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $dam3dam['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($dam3dam)); ?><br>
                      <small>(Dam’s Sire's Dam)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td rowspan="2">
                  <?php echo stripslashes(renderHorseName($dam2dam)); ?><br>
                  <small>(Dam’s Dam)</small>
                  <?php //echo getHorseImage($conn, $baseURL, $dam2dam['id']); ?>
                </td>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $dam3sire2['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($dam3sire2)); ?><br>
                      <small>(Dam’s Dam’s Sire)</small>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="pedigree-item">
                    <?php //echo getHorseImage($conn, $baseURL, $dam3dam2['id'], 50); ?>
                    <div class="pedigree-name">
                      <?php echo stripslashes(renderHorseName($dam3dam2)); ?><br>
                      <small>(Dam’s Dam’s Dam)</small>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>



          </table>
        </div>

      </div> <!-- modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- More Info Modal -->
<div class="modal fade" id="moreInfoModal" tabindex="-1" aria-labelledby="moreInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="moreInfoModalLabel">
          More Information – <?php echo htmlspecialchars($rowproduct['name']); ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        if (!empty($rowproduct['text1'])) {
            echo $rowproduct['text1'];
        } else {
            echo "<p class='text-muted fst-italic'>No additional information available.</p>";
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Placeholder for pedigree modal include -->
<?php
// include 'includes/modal_pedigree.php';  // will be created later
?>
