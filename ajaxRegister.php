<?php

require_once("config/config.php");
require_once("config/phpFuncts.php");


mysql_connect($sqlAddress, $sqlUser, $sqlPass);
mysql_select_db($sqlDB);//Can't use connect.php because we haven't authenticated

$user = post('user');
$pass = post('pass');
$name = post('name');
$email = post('email');
$phone = post('phone');
$code = post('code');//get Variable

validateInputs($user, $name, $email, $phone, $code);

$salt = md5($saltKey);
$encoded = md5($salt.$pass);//Secure your password
//Error Checking//

$sql = "SELECT `id` FROM users WHERE `user`='".$user."'";
if(!mysql_query($sql)) //Check if user exists
	error("Username already in use");

$sql = "SELECT `id`, `open_register` FROM groups WHERE `code`='".$code."'";
$data = mysql_query($sql); //Check if group code matches any groups
if(!$data)
	error("Group Code Invalid");

$row = mysql_fetch_array($data);

if($row['open_register'] == 0)
	error("Group not open for registration"); //Check if matching group is accepting users

$sql = "INSERT INTO users (`user`, `password`, `name`, `email`, `phone`, `group_id`";
$sql .= ") VALUES (";
$sql .= "'".$user."', '".$encoded."', '".$name."', '".$email."', '".$phone."', ".$row['id'].")";

mysql_query($sql); //Create user


function validateInputs($user, $name, $email, $phone, $code) {
	$passed = "";

	$passed = (sanitizeString($user) == $user) ? $passed : "Invalid User";

	$passed = (sanitizeString($name, true) == $name) ? $passed : "Invalid Name";

	$passed = verifyEmail($email) ? $passed : "Invaid Email";

	$passed = verifyPhone($phone) ? $passed : "Invalid Phone";

	$passed = (sanitizeString($code) == $code) ? $passed : "Invalid Group Code";

	if($passed != "")
		error($passed);
}
?> 
