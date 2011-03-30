<?

require_once("config/config.php");
require_once("config/phpFuncts.php");

$path = $uploadDir;//Uses directory from config.php

$name = sanitizeString(basename($_FILES['uploadfile']['name']));//grap file name
$spec_name = strip_tags(URLDecode($name));//Format how the SQL will see it. 
$path .=  $spec_name;//Can't decide if addslashes or stripslashes would be good for security

move_uploaded_file($_FILES['uploadfile']['tmp_name'], $path);//MOVE IT!
?>
