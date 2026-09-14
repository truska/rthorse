<!-- START content-contact id: 7 | <?php echo $rowcontent["title"]; ?> -->

<style>
    .map-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    border-radius: 8px; /* optional */
    }

    .map-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
    }

</style>
<?php

echo "<div class='container parent-element'>" ;

	showEditButton(3, $rowcontent["id"]);

	echo "<div class='row'>";



		// --- Heading ---
		echo "<div class='col-12 content-{$rowcontent['id']}'>";
			echo "<a name='{$rowcontent['id']}'></a>";
			if ($rowcontent["showheading"] == 'Yes') {
				echo "<h1>{$rowcontent['heading']}</h1>";
			}

            echo "<div class='row'>" ;

                echo "<div class='col-12 col-md-6'>" ;
                // Semi hardcoded - conatc details
                echo "<h3>".$rowcontent["subheading"]."</h3>";

                echo "<p><i class='fa-solid fa-location-dot'></i>&nbsp;&nbsp;".getAddressShort($prefs)."</p>" ;
                echo "<p><a href='tel:".str_replace(' ', '', getTel1Int($prefs))."'>" ;
                echo "<i class='fa-solid fa-phone'></i>&nbsp;&nbsp;".getTel1($prefs) ;
                echo "</a></p>" ;
                    
                if(getEmail($prefs)) {
                        echo "<p><a href='mailto:".getEmail($prefs)."' target='_blank'><i class='fa-solid fa-at'></i>&nbsp;&nbsp;".getEmail($prefs)."</a></p>" ;
                    }

                    
                echo "".$rowcontent["text"]."";

                echo "</div>" ;

                echo "<div class='col-12 col-md-6'>" ;
                echo "<h3>".$rowcontent["subheading2"]."</h3>";
                echo "".$rowcontent["text2"]."";

                echo "</div>" ;



                echo "<div class='col-12'>" ;
                    ?>


                    <!-- Responsive Google Map Embed -->
                    <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d23515.339643235962!2d-6.258296165604762!3d54.6520340369087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48605510ad27009f%3A0x163d256221b30a32!2sRoundThorn%20Sport%20Horses!5e1!3m2!1sen!2suk!4v1762879529911!5m2!1sen!2suk"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    </div>

                   
                    <?php
                echo "</div>" ;

            echo "</div>" ;




		echo "</div>";

	echo "</div>";

echo "</div>";
?>
<!-- END content-contact -->


	