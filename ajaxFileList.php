<?php
require_once("config/connect.php");

	if(isset($_SESSION['page']))
		$page = $_SESSION['page'];
	else
		$page = 1;

	$incre = $_GET['incre'];
	$page += $incre;

	if(!is_numeric($incre))
		error("Invalid Inputs");

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
	<?php } else {
		while(($row = mysql_fetch_array($data)) && $cur <= 20) {
			extract($row, EXTR_PREFIX_ALL, "r");//Break array into seperate vars
			$revision = $r_is_revision ? "Yes" : "No";
			$tm = strtotime($r_upload_date);//Convert timestamp to readable format
			$strtm = date("g:i A \o\\n n/j/y", $tm);
	?>
		<div class="logItem
		<?php
			if($r_is_downloaded)
				echo " downloaded";
		?>">
			<span class="fLeft"><img id="<?php echo $r_id;?>" class="ex" src="images/delete.png" /><?php echo $r_file_name;?></span>
			<span class="fRight"><?php echo (round(intval($r_file_size)/1048576, 2))." MB";?>
			<a class="spanLink nobubble" href="javascript:downloadFile(<?php echo $r_id;?>)">Download</a>
			<?php if($r_has_preview) { ?>
			<a class="spanLink nobubble" href="javascript:prepImage(<?php echo $r_id;?>, '<?php echo $r_file_name;?>');">Preview</a>
			<?php } ?>
			</span>
			<br>
			<div class="extra">
				<div class="moreInfo">
					<b>Contact Name:</b> <?php echo $r_name;?><br>
					<b>Email:</b> <?php echo $r_email;?><br>
					<b>Phone:</b> <?php echo $r_phone;?><br>
				</div>
				<div class="moreInfo">
					<b>Uploaded By:</b> <?php echo $r_upload_by;?><br>
					<b>Time:</b> <?php echo $strtm;?><br>
				</div>
				<div style="margin-right: 10px; padding-right: 10px; border-right: 1px solid #445566;" class="moreInfo">
					<b>OS:</b> <?php echo $r_os;?><br>
					<b>Is Revision:</b></b> <?php echo $revision;?><br>
				</div>
				<div class="moreInfo">
					<?php echo $r_detail;?>
				</div>
				<div class="clearboth"></div>
			</div>
		</div>
		<center>
	<?php $cur++;
	}
		if($offset > 0) 
			echo "<a style='margin-right: 5px' class='spanLink nobubble' href='javascript:newPage(-1);'><--</a>";
		echo $page." / ".(ceil($num / 20));
		if($offset < ($num - 20))
			echo "<a style='margin-left: 5px' class='spanLink nobubble' href='javascript:newPage(1);'>--></a>";
		}		
		?>
</center>
