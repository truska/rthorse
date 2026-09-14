<!-- START header -->
<style>
  /* --- DESKTOP --- */
  .logo-fixed {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    background: #fff;
    padding: 0.5rem 1rem;
  }

  .contact-fixed {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 1050;
    background: #fff;
    padding: 1rem;
    text-align: right;
  }

  header {
    padding-top: 10px;
  }

  .header-row {
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 0.5rem 1rem;
  }

  .headerlogo {
    max-height: 140px !important;
  }

  .header1 {
    font-size: 4rem;
    color: darkolivegreen;
    font-weight: 700;
  }

  .header2 {
    font-size: 1.25rem;
  }

  .headerarea .fa-solid {
    font-size: xx-large;
    color: darkolivegreen;
    transition: color 0.3s;
  }

  .headerarea a {
    color: inherit;
    text-decoration: none;
  }

  .headerarea a:hover .fa-solid {
    color: olive;
  }

  /* --- MOBILE --- */
  @media (max-width: 767.98px) {
    .logo-fixed,
    .contact-fixed {
      display: none !important; /* hide fixed logo/icons on mobile */
    }

    .header1 {
      font-size: 1.5rem;
      text-align: center;
    }

    .header2 {
      font-size: 1rem;
      text-align: center;
    }

    .mobile-header-row {
      align-items: center;
      padding: 0.5rem 1rem;
    }

    .mobile-logo {
      max-height: 150px;
    }

    .mobile-icons  {
    text-align: center;
    }
    .mobile-icons .fa-solid {
      font-size: 1.0rem;
      margin-right: 2rem;
    text-align: center;
    }
  }
</style>

<header>
  <!-- Fixed left logo -->
  <div class="logo-fixed">
    <a href='<?php echo $baseURL; ?>'>
      <img
        src="<?php echo $baseURL; ?>/filestore/images/logos/<?php echo getLogo($prefs); ?>"
        alt="<?php echo getSiteName($prefs); ?>"
        class="img-fluid headerlogo">
    </a>
  </div>

  <!-- Fixed right contact icons -->
  <div class="contact-fixed headerarea">
    <a href="tel:<?php echo preg_replace('/\s+/', '', $prefs['prefTel1']); ?>"><i class="fa-solid fa-phone"></i></a>&nbsp;&nbsp;
    <a href="mailto:<?php echo $prefs['prefEmail']; ?>"><i class="fa-solid fa-at"></i></a>&nbsp;&nbsp;
    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($prefs['prefAddress']); ?>" target="_blank"><i class="fa-solid fa-location-dot"></i></a>
  </div>

  <!-- DESKTOP header -->
  <div class="container-fluid d-none d-md-block">
    <div class="row header-row text-center">
      <div class="col-12">
        <p class="header1 mb-1"><?php echo getSiteName($prefs); ?></p>
        <p class="header2 mb-0">
          <!--Breeding and producing top quality sport horses for the three Olympic disciplines of showjumping, eventing and dressage-->
          <?php echo getTagline($prefs); ?></p>
        </p>
      </div>
    </div>
  </div>

  <!-- MOBILE header -->
  <div class="container-fluid d-md-none">
    <div class="row mobile-header-row">


		<div class="col-12text-start">
		<p class="header1 mb-1"><?php echo getSiteName($prefs); ?></p>
		</div>

      <div class="col-3 text-start">
        <img
          src="<?php echo $baseURL; ?>/filestore/images/logos/<?php echo getLogo($prefs); ?>"
          alt="<?php echo getSiteName($prefs); ?>"
          class="img-fluid mobile-logo"
        >
      </div>

      <div class="col-9">
        <!--<p class="header1 mb-1">Roundthorne Sport Horses</p> -->
        <!-- optional tagline -->
        <p class="header2 mb-2">
          Breeding and producing top quality sport horses
		
        </p>

        <div class="mobile-icons headerarea mt-1">
          <a href="tel:<?php echo preg_replace('/\s+/', '', $prefs['prefTel1']); ?>"><i class="fa-solid fa-phone"></i></a>
          <a href="mailto:<?php echo $prefs['prefEmail']; ?>"><i class="fa-solid fa-at"></i></a>
          <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($prefs['prefAddress']); ?>" target="_blank"><i class="fa-solid fa-location-dot"></i></a>
        </div>
      </div>



    </div>
  </div>
</header>
<!-- END header -->
