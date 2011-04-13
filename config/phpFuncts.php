<?php
function post($val) {
	return addslashes(strip_tags(URLDecode($_POST[$val])));
}
function error($msg) {
	header("HTTP/1.0 555 ".$msg);
	die();
}
function sanitizeString($pre, $allowSpaces = false) {

	if($allowSpaces)
		return preg_replace('/[^a-zA-Z0-9.\s]/', '_', $pre);
	else
		return preg_replace('/[^a-zA-Z0-9.]/', '_', $pre);
	
}
function verifyEmail($email) {

	$parts = explode('@', $email);
		
	if(sizeof($parts) == 2) {
		if(!strpos($parts[1], "."))
			return false;
	} else { 
		return false;
	}

	return true;
}
function verifyPhone($number) {
	$badChars = array("-", "(", ")", ".", "_", "/", "\\", " ");
	$clean = str_replace($badChars, '', $number);

	if(!is_numeric($clean))
		return false;

	$len = strlen($clean);


	//These are valid phone number lengths.
	//12345678901 (1-234-567-8901) = 11 chars
	//2345678901 (234-567-8901) = 10 chars
	//5678901 (567-8901) = 7 chars
	//Any more?
	if($len == 11 || $len == 10 || $len == 7)
		return true;
	else
		return false;
}
function adminPage() {
    if(!isAdmin()) {
        goHome();
    }
}
function isAdmin() {
    if(isset($_SESSION['is_admin'])) {
        if($_SESSION['is_admin']) {
            return true;
        }
    }
    return false;
}
function goHome() {
    header("Location: index.php");
    die();
}
?>
