<?
require_once("connect.php");

	if(isset($_SESSION['page']))
		$page = $_SESSION['page'];
	else
		$page = 1;

	$incre = $_GET['incre'];
	$page += $incre;

	$_SESSION['page'] = $page;
	
	$gid = $_SESSION['gid'];

	$sql = "SELECT * FROM files WHERE `group_id`=".$gid." ORDER BY `is_downloaded`, `upload_date`";
	$data = mysql_query($sql);//Get current groups files

	$num =  mysql_num_rows($data);
	$offset = ($page - 1) * 20;

	while($offset > $num) {
		$page--;
		$offset = ($page - 1) * 20;
	}

	mysql_data_seek($data, $offset);

	$cur = 1;
	if(mysql_num_rows($data) < 1) { ?>
	No Files are Currently Available
	<? } else { 
		while(($row = mysql_fetch_array($data)) && $cur <= 20) {
			extract($row, EXTR_PREFIX_ALL, "r");//Break array into seperate vars
			$revision = $r_is_revision ? "Yes" : "No";
			$tm = strtotime($r_upload_date);//Convert timestamp to readable format
			$strtm = date("g:i A \o\\n n/j/y", $tm);
	?>
		<div class="logItem
		<?
			if($r_is_downloaded)
				echo " downloaded";
		?>">
			<span class="fLeft"><?=$r_file_name;?></span>
			<span class="fRight"><? echo (round(intval($r_file_size)/1048576, 2))." MB";?>
			<a class="spanLink nobubble" href="javascript:downloadFile(<?=$r_id;?>)">Download</a>
			<? if($r_has_preview) { ?>
			<a class="spanLink nobubble" href="javascript:prepImage(<?=$r_id;?>, '<?=$r_file_name;?>');">Preview</a>
			<? } ?>
			</span>
			<br>
			<div class="extra">
				<div class="moreInfo">
					<b>Contact Name:</b> <?=$r_name;?><br>
					<b>Email:</b> <?=$r_email;?><br>
					<b>Phone:</b> <?=$r_phone;?><br>
				</div>
				<div class="moreInfo">
					<b>Uploaded By:</b> <?=$r_upload_by;?><br>
					<b>Time:</b> <?=$strtm;?><br>
				</div>
				<div style="margin-right: 10px; padding-right: 10px; border-right: 1px solid #445566;" class="moreInfo">
					<b>OS:</b> <?=$r_os;?><br>
					<b>Is Revision:</b></b> <?=$revision;?><br>
				</div>
				<div class="moreInfo">
					<?=$r_detail;?>
				</div>
				<div class="clearboth"></div>
			</div>
		</div>
		<center>
	<? $cur++;
	}
		if($offset > 0) 
			echo "<a style='margin-right: 5px' class='spanLink nobubble' href='javascript:newPage(-1);'><--</a>";
		echo $page." / ".(ceil($num / 20));
		if($offset < ($num - 20))
			echo "<a style='margin-left: 5px' class='spanLink nobubble' href='javascript:newPage(1);'>--></a>";
		}		
		?>
</center>
