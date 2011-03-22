<div id="footer">
<?
$fo = fopen("revision.bld", "r");
$buildNum = fread($fo, filesize("revision.bld"));
fclose($fo);
?>
<a href="http://www.wegnerdesign.com/file-drop">fileDrop</a> by Joe Wegner.  Build <?=$buildNum;?>
</div>
