<?
require_once("config/connect.php");
include "config/imagine.php";

$name = addslashes(strip_tags(URLDecode($_POST['name'])));

$path = $uploadDir.$name;

makePreview($path);

?>
