<?php
$fo = fopen("config/revision.bld", "r");
$buildNum = fread($fo, filesize("config/revision.bld"));
fclose($fo);
?>
<script type="text/javascript">
function goLink(url) {
	document.location = url;
}
</script>
<div class="headLink" onClick="goLink('index.php')">File List</div>
<div class="headLink" onClick="goLink('uploadForm.php')">Upload</div>

<?php if(isset($_SESSION['is_admin'])) {
        if($_SESSION['is_admin']) { ?>
	<div class="headLink" onClick="goLink('groups.php');">Groups</div>
<?php   }
      }?>
<div id="aboutus">fileDrop by Joe Wegner.<br>  Build <?php echo $buildNum;?></div>
<div class="headLink fRight" onClick="goLink('logout.php');">Log Out</div>
<div class="clearboth"></div>
