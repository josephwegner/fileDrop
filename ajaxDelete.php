<?
require_once("config/connect.php");

$gid = $_SESSION['gid'];
$id = addslashes(strip_tags(URLDecode($_POST['id'])));

if(!is_numeric($id))
	error("Invalid Input");

$sql = "SELECT `file_name` FROM files WHERE `group_id`=".$gid." AND `id`=".$id;

$data = mysql_query($sql);

if(mysql_num_rows($data) == 1) {//Block SQL Injection or already-deleted files
	$sql = "DELETE FROM files WHERE `group_id`=".$gid." AND `id`=".$id;
	mysql_query($sql);
	
	$file = mysql_fetch_array($data);
	unlink($uploadDir.$file['file_name']);		
} else {
	error("File Not Available");
}


?>
