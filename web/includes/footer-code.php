<!-- START footer-code -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>



<?php
if ($prefs["prefCookieCheck"] == 'Yes') {
    include("includes/cookiealert.php");
    echo '<script src="'.$baseURL.'/js/cookiealert.min.js"></script>';
}
include("includes/footer-scripts.php");
?>

<!-- Initialize Swiper -->
<!--
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 5,
    spaceBetween: 60,
    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    pagination: { el: ".swiper-pagination", clickable: true },
    breakpoints: {
      "@0.00": { slidesPerView: 3, spaceBetween: 10 },
      "@0.75": { slidesPerView: 3, spaceBetween: 20 },
      "@1.00": { slidesPerView: 4, spaceBetween: 40 },
      "@1.50": { slidesPerView: 5, spaceBetween: 50 }
    }
  });

  var swiper2 = new Swiper(".mySwiperProduct", {
    slidesPerView: 5,
    spaceBetween: 60,
    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    pagination: { el: ".swiper-pagination", clickable: true },
    breakpoints: {
      "@0.00": { slidesPerView: 1, spaceBetween: 10 },
      "@0.75": { slidesPerView: 2, spaceBetween: 20 },
      "@1.00": { slidesPerView: 2, spaceBetween: 20 },
      "@1.50": { slidesPerView: 3, spaceBetween: 20 }
    }
  });
</script>
-->

<!-- 🔹 Modern dropdown + collapse logic -->
<!-- 🔹 Bootstrap dropdown + collapse logic -->
<!--
<script>
document.addEventListener('DOMContentLoaded', function () {

  // --- Nested dropdowns (if any future submenus) ---
  document.querySelectorAll('.dropdown-submenu > .dropdown-toggle').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (window.innerWidth < 992) {
        e.preventDefault();
        e.stopPropagation();
        const submenu = el.nextElementSibling;
        if (!submenu) return;
        const parentMenu = el.closest('.dropdown-menu');
        if (parentMenu) {
          parentMenu.querySelectorAll('.dropdown-menu.show').forEach(function (m) {
            if (m !== submenu) m.classList.remove('show');
          });
        }
        submenu.classList.toggle('show');
      }
    });
  });

  // --- Auto-collapse entire nav after clicking a link (mobile only) ---
  document.querySelectorAll('.navbar .nav-link, .navbar .dropdown-item').forEach(function (link) {
    link.addEventListener('click', function () {
      const collapse = document.getElementById('mainNavbar');
      if (collapse && getComputedStyle(collapse).display !== 'none') {
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
        bsCollapse.hide();
      }
    });
  });
});
</script>

-->
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Close collapse ONLY when a link without dropdown is clicked
  document.querySelectorAll('.navbar .nav-link, .navbar .dropdown-item').forEach(link => {
    link.addEventListener('click', (e) => {
      // Skip dropdown toggles – let Bootstrap handle them
      if (link.classList.contains('dropdown-toggle')) return;

      const collapse = document.getElementById('mainNavbar');
      if (collapse && window.getComputedStyle(collapse).display !== 'none') {
        bootstrap.Collapse.getOrCreateInstance(collapse).hide();
      }
    });
  });
});
</script>

<!-- END footer-code -->
