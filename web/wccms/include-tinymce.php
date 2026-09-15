<!-- START include-tinymce -->

<?php

//Defaults
$tinymcemenubar = 'edit view insert format tools table help';

$tinymceheight = '300';

$tinymcetoolbar = 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media | removeformat | code';

$tinymceplugins = 'advlist autolink code image link lists media table';

//Get valuses from preferences

$sqlpref2 = "SELECT `name`,`value` FROM `preferences` WHERE `name` like 'prefTinyMCE%' ";
$querypref2 = mysqli_query($conn, $sqlpref2);

while ($respref1 = mysqli_fetch_assoc($querypref2)) {

    if ($respref1["name"] == "prefTinyMCEMenu") {
        $tinymcemenubar = $respref1["value"];
    } 

    if ($respref1["name"] == "prefTinyMCEHeight") {
        $tinymceheight = $respref1["value"];
    } 
	

    if ($respref1["name"] == "prefTinyMCEToolbar") {
        $tinymcetoolbar = $respref1["value"];
    } 

    if ($respref1["name"] == "prefTinyMCEPlugins") {
        $tinymceplugins = $respref1["value"];
    } 

}

?>


<script type="text/javascript">
    tinymce.init({
        selector: 'textarea#tinymcetextarea',
        license_key: 'gpl',
        menubar: '<?php echo $tinymcemenubar; ?>',
        height: '<?php echo $tinymceheight ; ?>',
        toolbar: '<?php echo $tinymcetoolbar ; ?>',
        plugins: '<?php echo $tinymceplugins ; ?>',

        entity_encoding: 'raw',
        forced_root_block: 'p',
        valid_elements: '*[*]',              // allow all elements and attributes
        extended_valid_elements: 'i[class],span[class],ul[class],li[class]',
        verify_html: false,                  // stops auto-cleaning of unknown HTML
        valid_classes: { '*': 'fa-ul,fa-li,fas,fa-horse,fa-tree,fa-globe,fa-comments,fa-trophy' },
        content_css: [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css'
        ],

        relative_urls: false,  // Disables converting absolute to relative URLs
        remove_script_host: false, // Keeps full URL with domain
        convert_urls: false,  // Prevents TinyMCE from modifying URLs
        document_base_url: "<?php echo $baseURL; ?>/" // Explicitly set base URL
    });
</script>

<!-- END include-tinymce -->
