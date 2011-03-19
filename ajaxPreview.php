<?
require_once("connect.php");
include "imagine.php";

$name = addslashes(strip_tags(URLDecode($_POST['name'])));

$path = $uploadDir.$name;

makePreview($path);

?>
