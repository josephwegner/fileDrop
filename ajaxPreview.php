<?php
require_once("config/connect.php");
include $imagineVer;

$name = addslashes(strip_tags(URLDecode($_POST['name'])));

$path = $uploadDir.$name;

makePreview($path);

?>
