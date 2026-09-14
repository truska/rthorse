<!-- START header-code -->

<title>wITeCanvas CMS - <?php echo $prefs["prefSiteName"]; ?></title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="wITeCanvas CMS System">
<meta name="author" content="wITeCanvas">
<meta name="keyword" content="wITeCanvas, cms, truska, digita">
<link rel="shortcut icon" href="img/witecanvas-favicon.ico">
<meta name="robots" content="noindex, nofollow" />

<!-- ✅ jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- ✅ Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional Reset / Slidebar / Wizard / Custom styles -->
<link href="css/bootstrap-reset.css" rel="stylesheet">
<link href="css/slidebars.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/jquery.steps.css" />
<link href="css/style.css" rel="stylesheet">
<link href="css/style-responsive.css" rel="stylesheet">

<!-- ✅ Font Awesome 6.4 (CDN with token) -->
<script src="https://kit.fontawesome.com/<?php echo $prefs["prefFontAwsomeToken"]; ?>" crossorigin="anonymous"></script>

<!-- ✅ Dropzone -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<!--
<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
-->

<!-- ✅ DataTables CSS (still used) -->
<!-- DataTables CSS for Bootstrap 5 -->
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">


<!-- Flatpickr Date picker for managed formating -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- ✅ TinyMCE -->
<?php if ($prefs["prefTinyMCE"]) { ?>
  <script src="https://cdn.tiny.cloud/1/<?php echo $prefs["prefTinyMCE"]; ?>/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<?php } else { ?>
  <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<?php } ?>

<!-- ✅ Optional dynamic CSS injection -->
<!--
<?php if ($customcss) echo "<style>{$customcss}</style>"; ?>
-->

<!-- ✅ Toast Notifications -->
<script src="/wccms/js/Toast.js"></script>

<!-- END header-code -->
