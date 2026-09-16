<!-- START footer -->
<style>
    .footer {
        background-color: var(--menu-top-bg);
        color: #fff;
        padding: 2rem 0;
        font-size: 0.95rem;
    }

    .footer a {
        color: #ddd;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .footer a:hover,
    .footer a:focus {
        color: yellow;
        text-decoration: none;
    }

    .footer ul {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .footer li {
        margin-bottom: 0.25rem;
        text-transform: uppercase;
    }

    .footer .contact-info p {
        margin-bottom: 0.25rem;
    }

    .footer .social-icons a {
        display: inline-block;
        margin-right: 0.75rem;
        font-size: 3rem;
        color: #fff;
    }

    .footer .social-icons a:hover {
        color: var(--brandred, yellow);
    }
    /* NEW — restore normal text size for non-icon links inside social section */
    .footer .social-icons .email-link,
    .footer .social-icons div a {
        font-size: 0.95rem;
        color: #ddd;
    }
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.2);
        margin-top: 2rem;
        padding-top: 1rem;
        font-size: 0.85rem;
    }

    .footer-bottom .left {
        text-align: left;
    }

    .footer-bottom .right {
        text-align: right;
    }

    @media (max-width: 767px) {
        .footer-bottom .left,
        .footer-bottom .right {
            text-align: center;
            margin-bottom: 0.75rem;
        }
    }
</style>

<div class="footer">
    <div class="container">
        <div class="row">

            <!-- Footer columns 1 + 2: Horses for Sale -->
            <div class="col-12 col-md-6 mb-4 mb-md-0">

                <h4 class="text-start">Horses for Sale</h4>

                <div class="row">
                    <?php
                        $selectforsale = "SELECT * FROM `products` 
                            WHERE `forsale` = 'Yes' 
                            AND `showonweb` = 'Yes' 
                            ORDER BY `name`";

                        $queryforsale = mysqli_query($conn, $selectforsale);

                        $horses = [];

                        while ($rowforsale = mysqli_fetch_assoc($queryforsale)) {
                            $horses[] = $rowforsale;
                        }

                        $half = ceil(count($horses) / 2);
                        $horseCols = array_chunk($horses, $half);

                        foreach ($horseCols as $horseCol) {
                            echo "<div class='col-6'>";
                                foreach ($horseCol as $rowforsale) {
                                    echo "<p>";
                                        echo "<i class='fa-solid fa-gavel text-warning'></i> ";
                                        echo "<a href='{$baseURL}/horse/{$rowforsale["id"]}/{$rowforsale["slug"]}'>{$rowforsale["name"]}</a>";
                                    echo "</p>";
                                }
                            echo "</div>";
                        }
                    ?>
                </div>

            </div>

            <!-- Footer column 3: contact -->
            <div class="col-6 col-md-3 mb-4 mb-md-0 contact-info">
                <h4>Find Us</h4>

                <p>
                    <i class="fa-solid fa-location-dot"></i>&nbsp;&nbsp;
                    <?= getAddressShort($prefs); ?>
                </p>

                <p>
                    <a href="tel:<?= str_replace(' ', '', getTel1Int($prefs)); ?>">
                        <i class="fa-solid fa-phone"></i>&nbsp;&nbsp;
                        <?= getTel1($prefs); ?>
                    </a>
                </p>

                <?php if (getEmail($prefs)) : ?>
                    <p>
                        <a href="mailto:<?= getEmail($prefs); ?>" target="_blank">
                            <i class="fa-solid fa-at"></i>&nbsp;&nbsp;
                            <span class="d-none d-md-inline"><?= getEmail($prefs); ?></span>
                            <span class="d-inline d-md-none">Email</span>
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Footer column 4: social -->
            <div class="col-6 col-md-3 social-icons text-md-end">
                <h4>Find us in Socials</h4>

                <?php
                    if ($prefs['prefFacebookURL']) {
                        echo "<a href='".$prefs['prefFacebookURL']."' target='_blank'><i class='".$prefs['prefFacebookImage']."'></i></a>";
                    }
                    if ($prefs['prefInstagramURL']) {
                        echo "<a href='".$prefs['prefInstagramURL']."' target='_blank'><i class='".$prefs['prefInstagramImage']."'></i></a>";
                    }
                    if ($prefs['prefTwitterURL']) {
                        echo "<a href='".$prefs['prefTwitterURL']."' target='_blank'><i class='".$prefs['prefTwitterImage']."'></i></a>";
                    }
                    if ($prefs['prefLinkedinURL']) {
                        echo "<a href='".$prefs['prefLinkedinURL']."' target='_blank'><i class='".$prefs['prefLinkedinImage']."'></i></a>";
                    }
                ?>
            </div>

        </div>

        <!-- Footer bottom row -->
        <div class="row footer-bottom align-items-center">
            <div class="col-12 col-md-6 left">
                <p>
                    <a href="<?php echo $baseURL; ?>/privacy-policy">Privacy Policy</a> | 
                    <a href="<?php echo $baseURL; ?>/terms-of-use">Terms of Use</a><br>
                    &copy; <?php echo getCompanyName($prefs)." ".$prefs['prefCopyrightStartYear']." - ".date('Y'); ?>
                </p>
            </div>
            <div class="col-12 col-md-6 right">
                <a href="https://truska.com" target="_blank">
                    designed &amp; coded by truska
                </a><br>
                <span>IP: <?php echo $_SERVER['REMOTE_ADDR']; ?> | Prefs: <?php echo $prefs['prefTruskaIP']; ?></span>
            </div>
        </div>
    </div>
</div>
<!-- END footer -->
