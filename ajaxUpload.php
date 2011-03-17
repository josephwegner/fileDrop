<?
	require_once("connect.php");
	require_once("imagine.php");

	$name = post('name');
	$email = post('email');
	$phone = post('phone');
	$user = post('user');
	$group = post('group');
	$detail = post('detail');
	$os = post('os');
	$revision = post('revision');
	$file = post('file');
	$size = post('size');//Grab Vars

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
function post($val) {
	return addslashes(strip_tags(URLDecode($_POST[$val])));
}
?>
