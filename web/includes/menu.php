<?php
// includes/menu.php
?>
<style>
    .menu-search-form {
        min-width: 180px;
    }
    .menu-search-item {
        display: flex;
        align-items: center;
        padding: 0 0.5rem;
    }
    .menu-search-form .form-control {
        border-radius: 20px;
    }
    .menu-search-form .btn {
        border-radius: 50%;
        padding: 0.3rem 0.5rem;
    }

    /* Adjust menu search colours */
    .menu-search-form .btn {
    color: var(--menu-search-icon, #555);       /* icon colour */
    background-color: var(--menu-search-hover-bg, #fff);
    border-color: var(--menu-search-border, #aaa);
    }

    .menu-search-form .btn:hover {
    background-color: var(--menu-search-hover-bg, #ccc);
    color: var(--menu-search-hover-color, #000);
    }
  </style>
  <?php
require_once __DIR__ . '/../controllers/menu.php';
renderMenu($conn, $baseURL, 'below');   // or 'inline' if inside header
?>
