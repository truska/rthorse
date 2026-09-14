<!-- START google.php -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<?php
if ($prefs['prefGoogleAnalyticsCode'] ) {
?>

<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $prefs['prefGoogleAnalyticsCode'] ; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $prefs['prefGoogleAnalyticsCode'] ; ?>');
</script>

<?php
}
?>
<!-- END google.php -->