<?php

require_once("config/connect.php");

$g_name = sanitizeString(post('g_name'));
$open_register = post('open_register');

$a_user = post('user');
$a_pass = post('pass');
$a_name = post('a_name');
$a_email = post('email');
$a_phone = post('phone');//Grab Variables

validateInputs($open_register, $a_user, $a_name, $a_email, $a_phone);

$sql = "SELECT `id` FROM groups WHERE `name`='" . $g_name . "'";

if(!mysql_query($sql)) //Check if group already exists
	error("Group already exists");

$sql = "SELECT `id` FROM users WHERE `user`='".$a_user."'";

if(!mysql_query($sql)) //Check if user already exists
	error("User already exists");

$g_code = substr(md5($g_name), 0, 7); //First 7 chars of MD5'd group name
$salt = md5($saltKey);
$encoded = md5($salt.$a_pass);

$sql = "INSERT INTO groups (`name`, `code`, `open_register`)";
$sql .= " VALUES ('".$g_name."', '".$g_code."', ".$open_register.")";

mysql_query($sql); //Create group

$group_id = mysql_insert_id(); //Get Group ID

$dir = $uploadDir_Web.$group_id;
mkdir($dir);//Create directory to hold group's files.

$sql = "INSERT INTO users (`user`, `password`, `group_id`, `can_download`, `phone`, `email`, `name`";
$sql .= ") VALUES (";
$sql .="'".$a_user."', '".$encoded."', ".$group_id.", 1, '".$a_phone."', '".$a_email."', '".$a_name."')";

mysql_query($sql); //Create Admin User

$admin_id = mysql_insert_id(); //Get Admin ID

$sql = "UPDATE groups SET `admin_id`=".$admin_id." WHERE `id`=".$group_id;

mysql_query($sql);//Update group with admin ID



function validateInputs($open_register, $a_user, $a_name, $a_email, $a_phone) {
	$passed = "";
	
	$passed = (is_numeric($open_register)) ? $passed : "Invalid Registration";

	$passed = (sanitizeString($a_user) == $a_user) ? $passed : "Invalid User";

	$passed = (sanitizeString($a_name, true) == $a_name) ? $passed : "Invalid Name";

	$passed = verifyEmail($a_email) ? $passed : "Invalid Email";

	$passed = verifyPhone($a_phone) ? $passed : "Invalid Phone";

	if($passed != "")
		error($passed);
}
?>
