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
<!-- jQuery UI must be available before CMS page scripts initialise sortable. -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">

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

<!-- Self-hosted TinyMCE Community. This header is loaded by every CMS form. -->
<script src="<?php echo htmlspecialchars(rtrim($baseURL, '/') . '/wccms/js/tinymce/tinymce.min.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.tinymce === 'undefined') {
            console.error('TinyMCE did not load from the local CMS asset.');
            return;
        }

        // The ID selector keeps cached legacy form output working while the
        // class selector supports the corrected multi-editor form markup.
        document.querySelectorAll('textarea.tinymcetextarea, textarea#tinymcetextarea').forEach(function (textarea) {
            window.tinymce.init({
                target: textarea,
                license_key: 'gpl',
                height: 400,
                menubar: 'edit view insert format tools table help',
                plugins: 'advlist autolink code image link lists media table',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media | removeformat | code',
                entity_encoding: 'raw',
                forced_root_block: 'p',
                convert_urls: false,
                promotion: false
            });
        });
    });
</script>

<!-- ✅ Optional dynamic CSS injection -->
<!--
<?php if ($customcss) echo "<style>{$customcss}</style>"; ?>
-->

<!-- ✅ Toast Notifications -->
<script src="/wccms/js/Toast.js"></script>

<!-- END header-code -->
