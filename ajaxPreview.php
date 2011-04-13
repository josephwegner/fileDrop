<?php
require_once("config/connect.php");
include $imagineVer;

$gid = $_SESSION['gid'];


if(isAdmin()) {
    if(!is_numeric($_GET['id'])) {
        error("Invalid ID");
    }
    $id = $_GET['id'];

    $sql = "SELECT `group_id` FROM files WHERE `id`=".$id;
    $data = mysql_query($sql);
    $row = mysql_fetch_array($data);
    $gid = $row['group_id'];
}

$name = addslashes(strip_tags(URLDecode($_POST['name'])));

$path = $uploadDir.$gid."/".$name;

makePreview($path);

?>
