<!-- START footer-scripts -->
 
<?php
    echo $prefs['prefFooterCode'] ;     // Global footer code

    if($rowpage['footercode']) {
        echo $rowpage['footercode'] ;   // Page specific Footercode
        $footercode ;
    }

    if($prefs["prefShowChat"] == 'Yes') {
        if($rowpage['chatCode']) {
            echo $rowpage['chatCode'] ; // Chat footer code - Embed API
        }
    }
    
?>

<!-- END footer-scripts -->