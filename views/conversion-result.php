<?php include 'header.php'; ?>
<textarea>
<?php echo str_replace( '<', '&lt;', str_replace( '&', '&amp;', $converted ) ); ?>
</textarea>
<?php include 'footer.php'; ?>