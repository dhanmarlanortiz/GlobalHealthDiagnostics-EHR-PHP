
<?php
require 'header.php';
?>

<?php
$pdfFilePath = base_url(false) . '/uploads/file-sample--150kB_1699948873.pdf';
$dummyBaseUrl = base_url(false) . '/uploads';
?>

<div>
    <iframe class="w-full min-h-full" src="<?php echo $pdfFilePath; ?>" frameborder="0"></iframe>
</div>
<?php 
require 'footer.php';
?>

