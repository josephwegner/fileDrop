<?
$sqlAddress = "localhost"; //Network Location for your MySQL
$sqlUser = "root"; //User for your MySQL
$sqlPass = "password"; //Password for you MySQL
$sqlDB = "ftp"; //Name of the database you will be using

$saltKey = "53cRe7k3Y"; //A random string of characters used to salt passwords.

$uploadDir = "/var/uploads/"; //Directory that files will be uploaded to.  This should be from your SYSTEM's root directory -- NOT the webserver root.
$previewDir = "/var/preview/"; //Directory that file previews will go.  Same rules as above

?>
