<?php
$sqlAddress = "localhost"; //Network Location for your MySQL
$sqlUser = "root"; //User for your MySQL
$sqlPass = "password"; //Password for you MySQL
$sqlDB = "ftp"; //Name of the database you will be using

$saltKey = "SecretKey"; //A random string of characters used to salt passwords.

$uploadDir = "/var/uploads/"; //Directory that files will be uploaded to.  This should be from your SYSTEM's root directory
$uploadDir_Web = "../../uploads/";  //Same as above, but from your WEB SERVER's root directory.


$os = "windows"; //What operating system is fileDrop running on?  Values can be: windows, linux



/*********************************
 * End of configuration values
 * The code below should not be changed unless you are a PHP guru
 * This config section sets system variables based on your input above
 *********************************/

$imagineVer = ($os == "windows") ? "config/w_imagine.php" : "config/imagine.php";

?>
