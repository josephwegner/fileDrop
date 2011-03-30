<? 
session_start();

require_once("config.php");
require_once("phpFuncts.php");

if(!isset($_SESSION['user_id'])) {
	include "login.php";//block unauthenticated users
	die();
}

mysql_connect($sqlAddress, $sqlUser, $sqlPass);
mysql_select_db($sqlDB);//Open MySQL with config.php settings
?>
