<?

require_once("config.php");

$path = $uploadDir;//Uses directory from config.php
$path .= basename($_FILES['uploadfile']['name']);//grap file name

move_uploaded_file($_FILES['uploadfile']['tmp_name'], $path);//MOVE IT!
?>
