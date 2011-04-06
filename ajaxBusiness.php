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

	$sql = "SELECT files.*, sub.group_id FROM files, ";
    $sql .= "(SELECT MAX(`upload_date`) AS gpOrder, `group_id` FROM files ";
    $sql .= "GROUP BY `group_id`) AS sub WHERE sub.group_id=files.group_id";
    $sql .= " ORDER BY sub.gpOrder DESC, files.upload_date DESC";

	$data = mysql_query($sql);//Get current groups files

	$sql = "SELECT `name` FROM groups WHERE `id`=".$gid;

	$group_d = mysql_query($sql);

	$group_p = mysql_fetch_array($group_d);

	$group = $group_p['name'];

	$num =  mysql_num_rows($data);
	$offset = ($page - 1) * 20;

	while($offset > $num) {
		$page--;
		$offset = ($page - 1) * 20;
	}

	if(mysql_num_rows($data))
		mysql_data_seek($data, $offset);

	//Variables for file sizes later
	$gb = 1073741824;//1GB in bytes
	$mb = 1048576;//1MB in bytes
	$kb = 1024;//1KB in bytes

            $curGroup = -1;
			$cur = 1;
			if(mysql_num_rows($data) < 1) { ?>
			No Files are Currently Available<br>
			<?php } else {
				while(($row = mysql_fetch_array($data)) && $cur <= 20) {
					extract($row, EXTR_PREFIX_ALL, "r");//Break array into seperate vars

                    if($curGroup != $r_group_id) {
                        $curGroup = $r_group_id;
                        $sql = "SELECT `name` FROM groups WHERE `id`=".$curGroup;
                        $grpDat = mysql_query($sql);
                        $grpArr = mysql_fetch_array($grpDat);

                        echo "<div class='groupHead'>".$grpArr['name']."</div><hr>";

                    }

					$revision = $r_is_revision ? "Yes" : "No";
					$tm = strtotime($r_upload_date);//Convert timestamp to readable format
					$strtm = date("g:i A \o\\n n/j/y", $tm);
					
					if($r_file_size >= $gb) {//Always get the right $r_file_size
						$size = round($r_file_size/$gb, 2);
						$txtSize = $size." GB";
					} else if($r_file_size >= $mb) {
						$size = round($r_file_size/$mb, 2);
						$txtSize = $size." MB";
					} else {
						$size = round($r_file_size/$kb, 2);
						$txtSize = $size." KB";
					}
				?>
				<div class="logItem
				<?php
					if($r_is_downloaded)
						echo " busDown";
				?>">
					<span class="fLeft"><img id="<?php echo $r_id;?>" class="ex" src="images/delete.png" /><?php echo $r_file_name;?></span>
					<span class="fRight"><?php echo $txtSize;?>
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
				
			<?php $cur++;	}
			}
			echo "<center>";
			if($offset > 0) 
				echo "<a style='margin-right: 5px' class='spanLink nobubble' href='javascript:newPage(-1);'><--</a>";

			$pgs = ceil($num / 20);
			echo "Page ".$page." / ".$pgs;
			if($offset < ($num - 20))
				echo "<a style='margin-left: 5px' class='spanLink nobubble' href='javascript:newPage(1);'>--></a>";
			
			?>
			</center>
			