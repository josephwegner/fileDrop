<?
session_start();

unset($_SESSION['user_id']);
unset($_SESSION['gid']);
unset($_SESSION['is_admin']);


header("Location: index.php");
//blank page right now.. just using this to logout for testing.
?>
