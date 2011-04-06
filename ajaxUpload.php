<?php
	require_once("config/connect.php");
	require_once("config/imagine.php");

	$name = sanitizeString(post('name'), true);
	$email = post('email');
	$phone = post('phone');
	$user = post('user');
	$group = post('group');
	$detail = sanitizeString(post('detail'), true);
	$os = sanitizeString(post('os'));
	$revision = post('revision');
	$file = sanitizeString(post('file'));
	$size = post('size');//Grab Vars

	validateInputs($email, $phone, $user, $group, $revision, $size);

	$sql = "SELECT `name` FROM users WHERE `id`=".$user;
	$dat = mysql_query($sql);//Get user name of currently logged on user
	$arr = mysql_fetch_array($dat);
	$user = $arr['name'];

	$sql = "INSERT INTO files (`file_name`, `file_size`, `upload_date`, `group_id`,";
	$sql .= " `name`, `email`, `phone`, `upload_by`, `detail`, `os`, `is_revision`";
	$sql .= ") VALUES (";
	$sql .= stringify($file).stringify($size)."CURRENT_TIMESTAMP, ".stringify($group);
	$sql .= stringify($name).stringify($email).stringify($phone).stringify($user);
	$sql .= stringify($detail).stringify($os).stringify($revision, true).")";

	mysql_query($sql);//Insert the file into database


	$id = mysql_insert_id();

	$preview = makePreview($id, $uploadDir.$file);

	if(in_array(get_ext(basename($file)), $available_extensions)) {
		$sql = "UPDATE files SET `has_preview`=1 WHERE `id`=".$id;
		mysql_query($sql);
	}	

function stringify($data, $end = false) {

	$fini = "'".$data."'";//Format string for sql insertion

	if(!$end)
		$fini .= ", ";//Don't add comment if it's the last field

	return $fini;
}

function validateInputs($email, $phone, $user, $group, $revision, $size) {
	$passed = true;

	$passed = is_numeric($user) ? $passed : false;

	$passed = is_numeric($group) ? $passed : false;

	$passed = is_numeric($revision) ? $passed : false;
	
	$passed = is_numeric($size) ? $passed : false;

	$passed = verifyEmail($email) ? $passed : false;

	$passed = verifyPhone($phone) ? $passed : false;

	if(!$passed)
		error("Invalid Inputs");
}
?>
