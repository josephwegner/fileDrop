<div id="footer">
<?php
$fo = fopen("config/revision.bld", "r");
$buildNum = fread($fo, filesize("config/revision.bld"));
fclose($fo);
?>
<a href="http://www.wegnerdesign.com/file-drop">fileDrop</a> by Joe Wegner.  Build <?php echo $buildNum;?>
</div>
