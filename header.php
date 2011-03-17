<script type="text/javascript">
function goLink(url) {
	document.location = url;
}
</script>
<div class="headLink" onClick="goLink('index.php')">File List</div>
<div class="headLink" onClick="goLink('uploadForm.php')">Upload</div>

<? if($_SESSION['is_admin']) { ?><!--Put things in here that you only want admins to see--!>
	<div class="headLink" onClick="goLink('groups.php');">Groups</div>
<? } ?>
<div class="headLink fRight" onClick="goLink('logout.php');">Log Out</div>
