<!-- START dashboard -->
<?php

// Turn off error reporting
error_reporting(0);
// Turn on error reporting
//error_reporting(1);
$pageType = '' ; // Used to prevent controller/formField.php from Loading on Preference editing page(s)
include('setting/main-top-files.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <?php
        include("controllers/dashboard.php");
        include("include/header-code.php");
   ?>
   <!-- Ensure Bootstrap CSS is included -->
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <script type="text/javascript">
        var counts = <?php echo json_encode($allCountsDataBySection); ?>;
        var sectionNames = <?php echo json_encode($sectionNames); ?>;
        console.log(counts);
    </script>

   <section id="container" data-top="dashboard|">
      <?php
      include("include/header.php");
      include("include/sidebar.php");
      ?>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
         <section class="wrapper">
            <!-- Container for the dynamic cards -->
            <div class="row state-overview d-flex justify-content-left">
               <div class="col-12"></div>

               <div class="col-sm-12 col-md-12">
                   <div class="block-flat" style="padding:0px 0px">
                       <div class="block-flat">
                           <div class="content">
                               <div class="row dash-cols">
                                   <div class="col-sm-12 col-md-12">
                                       <ul class="nav nav-tabs">
                                           <li class="nav-item">
                                               <a class="nav-link active" href="#profile" data-bs-toggle="tab">Welcome</a>
                                           </li>
                                           <li class="nav-item">
                                               <a class="nav-link" href="#stats" data-bs-toggle="tab">Stats</a>
                                           </li>
                                       </ul>

                                       <div class="tab-content mt-3">
                                           <div class="tab-pane fade show active" id="profile">
                                               <h2 class="hthin">Welcome to your Web Site Management</h2>
                                               <p></p>
                                               <div class="row">
                                                   <div class="col-md-5 col-sm-6">
                                                       <h3>Truska CMS</h3>
                                                       <h4>Site: <?php echo $prefs["prefCompanyName"];?></h4>
                                                       <h5>URL: <?php echo $_SERVER['SERVER_NAME'];?></h5>
                                                       <h5>User: <?php echo $user["username"];?></h5>
                                                   </div>
                                                   <div class="col-md-5 col-sm-6">
                                                       <div class="row">
                                                           <div class='col-md-4 col-sm-6 img-fluid'>
                                                                <?php
                                                                    $userimage = 'unisex-user-icon.jpg';                                               
                                                                    if($user["gender"] == 'Male') {$userimage = 'male-user-icon.jpg';}
                                                                    if($user["gender"] == 'Female') {$userimage = 'manager-female-user-icon.jpg';}
                                                                    if($user["gender"] == 'Unknown') {$userimage = 'unisex-user-icon.jpg';}
                                                                    if($user["image"]) {$userimage = $user["image"];}  
                                                                    
                                                                    echo "<img src='../filestore/images/wccms/".$userimage."' style='width:150px; padding-right:2px; '>";
                                                                ?>
                                                               
                                                           </div>
                                                           <div class='col-md-4 col-sm-6 img-fluid text-align:left'>
                                                               <img src='../filestore/images/logos/icon.png' style='max-width:150px; padding-left:2px '>
                                                           </div>
                                                       </div>
                                                   </div>
                                                   <?php
                                                   if ($remoteAddr == $truskaIP) {
                                                   ?>
                                                        <div class="col-md-5 col-sm-6">
                                                            <h5>Tech Stuff - Truska only</h5>
                                                            <p><a href="<?php echo $baseURL ;?>/wccms/admin-database-fields.php" class="btn btn-info btn-lg">Check Database</a></p>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                               </div>
                                           </div>

                                           <div class="tab-pane fade" id="stats">
                                               <div id="counts-container"></div>
                                               <script type="text/javascript">
                                                   document.addEventListener('DOMContentLoaded', function() {
                                                       var countsContainer = document.getElementById('counts-container');
                                                       Object.keys(counts).forEach(function(section) {
                                                           var sectionMainContainer = document.createElement('div');
                                                           sectionMainContainer.className = 'container my-3';
                                                           var sectionHeaderRow = document.createElement('div');
                                                           sectionHeaderRow.className = 'row';
                                                           var sectionHeaderCol = document.createElement('div');
                                                           sectionHeaderCol.className = 'col-12';
                                                           var header = document.createElement('h2');
                                                           header.textContent = sectionNames[section] ? sectionNames[section] : 'Section ' + section;
                                                           sectionHeaderCol.appendChild(header);
                                                           sectionHeaderRow.appendChild(sectionHeaderCol);
                                                           sectionMainContainer.appendChild(sectionHeaderRow);
                                                           var cardsRow = document.createElement('div');
                                                           cardsRow.className = 'row';
                                                           counts[section].forEach(function(countData) {
                                                               var colDiv = document.createElement('div');
                                                               colDiv.className = 'col-lg-3 col-md-6';
                                                               var cardSection = document.createElement('section');
                                                               cardSection.className = 'card';
                                                               var symbolDiv = document.createElement('div');
                                                               symbolDiv.className = 'symbol';
                                                               symbolDiv.style.backgroundColor = countData.colour;
                                                               var iElement = document.createElement('i');
                                                               iElement.className = 'fal ' + countData.symbol;
                                                               symbolDiv.appendChild(iElement);
                                                               var valueDiv = document.createElement('div');
                                                               valueDiv.className = 'value';
                                                               var h1Element = document.createElement('h1');
                                                               h1Element.className = 'count';
                                                               h1Element.innerHTML = countData.number;
                                                               var pElement = document.createElement('p');
                                                               pElement.innerHTML = countData.name;
                                                               valueDiv.appendChild(h1Element);
                                                               valueDiv.appendChild(pElement);
                                                               cardSection.appendChild(symbolDiv);
                                                               cardSection.appendChild(valueDiv);
                                                               colDiv.appendChild(cardSection);
                                                               cardsRow.appendChild(colDiv);
                                                           });
                                                           sectionMainContainer.appendChild(cardsRow);
                                                           countsContainer.appendChild(sectionMainContainer);
                                                       });
                                                   });
                                               </script>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
         </section>
      </section>
      <?php include("include/footer-code.php"); ?>
   </section>

<?php

$section = fetchUniqueSections();
$allCountsDataBySection = [];
$countsDataJson = json_encode($allCountsDataBySection);

if ($countsDataJson === false) {
    $countsDataJson = '[]';
}
?>

<!-- Output the counts to JavaScript -->

</body>
</html>
<!-- END dashboard -->
