<?php
require_once("config/connect.php");

$file_id = $_GET['id'];//Get variable
$gid = $_SESSION['gid'];

if(!is_numeric($file_id))
	error("Invalid Inputs");

if(isAdmin()) {
    $sql = "SELECT `group_id` FROM files WHERE `id`=".$file_id;
    $data = mysql_query($sql);
    $row = mysql_fetch_array($data);
    $gid = $row['group_id'];
}

$sql = "SELECT `file_name` FROM files WHERE `group_id`=".$gid." AND `id`='".$file_id."'";

$data = mysql_query($sql);//Get file data
$dat = mysql_fetch_array($data);
$file_name = $dat['file_name'];


header("Content-Disposition: attachment; filename=".$file_name);
header("Cache-Control: no-cache");//Block Caching and force a client download

$file = $uploadDir.$gid."/".$file_name;

$fo = fopen($file, "r");
$strang = fread($fo, filesize($file));
fclose($fo); //Read data from file and store in variable $strang

echo $strang; //Write file in plain text

$sql = "UPDATE files SET `is_downloaded`=1 WHERE `id`='".$file_id."'";

mysql_query($sql);

?>
