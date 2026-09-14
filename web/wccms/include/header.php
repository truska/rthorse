<!-- START header -->
<?php
$user_role = $USER->getUserRole();

?>
<header class="header white-bg">

    <div class="sidebar-toggle-box">
        <i class="fa fa-bars"></i>
    </div>
    <!--logo start-->
    <a href="dashboard.php" class="logo">W<span>it</span>eCanvas</a>
	
        <h3 class="d-flex justify-content-center" style="padding-top:10px;"><?php echo $prefs["prefCompanyName"]; ?></h3>

    <div class="top-nav d-flex justify-content-end" style="margin-top:-45px; ">
        <!--search & user info start-->
        <ul class="nav text-right pull-right  top-menu">

            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-bs-toggle="dropdown" class="dropdown-toggle" href="#">
                    <?php
                    if ($user['image'] == "") {
                        echo '<img class="logged-img-cms" alt="" src="'.$gaseURL.'/filestore/images/wccms/female-user-icon.png" style="max-width:50px;">';
                    } else {
                        echo '<img class="logged-img-cms" alt="" src="'.$gaseURL.'/filestore/images/wccms/' . $user['image'] . '" style="max-width:50px;"">';
                    }
                    ?>
                    <span class="username">Logged in as : <b><?php echo $user['firstname'] . " " . $user['surname']; ?></b></span>

                </a>
                <ul class="dropdown-menu extended logout dropdown-menu-right">
                    <div class="log-arrow-up"></div>

                    <li><a href="logout.php"><i class="fa fa-key"></i> Log Out</a></li>
                </ul>

            </li>

            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>

<!-- END header -->