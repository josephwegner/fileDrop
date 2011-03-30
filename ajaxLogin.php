<?
session_start();

require_once("config/config.php"); //init
require_once("config/phpFuncts.php");

mysql_connect($sqlAddress, $sqlUser, $sqlPass);
mysql_select_db($sqlDB);//Can't load from connect.php because we haven't authenticated yet.

$user = sanitizeString(post('user'));
$pass = post('pass');

$salt = md5($saltKey);//hashes salt from your config file

$encode_pass = md5($salt.$pass);

$sql = "SELECT `password`, `id`, `group_id` FROM users WHERE `user`='".$user."'";

$data = mysql_query($sql); //Grab logins that match the user
$row = mysql_fetch_array($data);
$get_pass = $row['password'];


if($get_pass == $encode_pass) { //Check against sent password
	$_SESSION['user_id'] = $row['id'];
	$_SESSION['gid'] = $row['group_id'];//Set session variables to allow access

	if($row['group_id'] == 1)
		$_SESSION['is_admin'] = true;//admin priveleges
} else {
	header("HTTP/1.0 555 Invalid Login");//Is this the best way to do this?
}




?>
