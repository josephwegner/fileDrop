<!DOCTYPE html>
<html>
<head>
<?php


	if(isset($_SESSION['page']))
		$page = $_SESSION['page'];
	else
		$page = 1;


	
	$gid = $_SESSION['gid'];

	$sql = "SELECT * FROM files WHERE `group_id`=".$gid." ORDER BY `is_downloaded`, `upload_date`";
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



	?>
<title>File List</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="config/infoBox.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    var aboutUs = new infoBox("aboutus", "http://www.wegnerdesign.com", [ "fRight" ]);

	$("#overlay").click(hideLightbox);

	$(".nobubble").live('click', function(event) {
		event.stopImmediatePropagation();//Nested clickables should not "click" the parent
	});

	$(".ex").live('click', function(event) {
		event.stopImmediatePropagation();

		if(!confirm("Delete File?"))
			return false;

		id = $(this).attr('id');

		$.ajax({
			type: "POST",
			url: "ajaxDelete.php",
			data: "id=" + id,
			success: function(msg) {
				$(".ex#" + id).parent().parent().slideUp(300, function() { $(this).remove(); });
				console.log(msg);
			},
			error: function(err1, err2, err3) {
				alert(err3);
			}
		});
	});

	$(".ex").live('mouseenter', function() { $(this).attr('src', 'images/delete_h.png'); });
	$(".ex").live('mouseleave', function() { $(this).attr('src', 'images/delete.png'); });	

	$(".logItem").each(function() {
		$.data(this, "show", "false");//Data is not slid down
	});
	
	$(".logItem").live('mouseenter', function() {
			$(this).css('background-color', '#112255');
	});		

	$(".logItem").live('mouseleave', function() {
		$(this).css('background-color', '');
	});

	$(".logItem").live('click', function() {
		var showed = $.data(this, "show");//Data is showing
		
		if(showed == "true") {//Check if it's already showing
			$(this).children(".extra").slideUp(500);
			$.data(this, "show", "false");
		} else {
			$(this).children(".extra").slideDown(500);
			$.data(this, "show", "true");
		}
	});
});
function newPage(incre) {
	$.ajax({
		type: "GET",
		url: "ajaxFileList.php",
		data: "incre=" + incre,
		success: function(msg) {
			$("#logSlide").html(msg);
			width = $("#logSlideHold").width();
		
			$("#logSlide").show();
			$("#log2").animate({'margin-left': (-1 * width)}, 300, function() {
				$("#log2").html($("#logSlide").html());
				$("#log2").css('margin-left', '0px');
			});
		}
	});
}
function downloadFile(id) {
	$("iframe#fram").attr('src', 'download.php?id=' + id);
	//The iframe will load download.php.  Download.php has headers that force
	//no cacheing as well as forcing a client download.
	//The actual data in download.php is a plaintext version of the file you are requesting
}
function prepImage(id, name) {
	$("#loader").show();
	$("#overlay").fadeIn(300, function() {

		if($("#loader").is(":visible")) {
			$.ajax({
				type: "POST",
				url: "ajaxPreview.php",
				data: {"name": name},
				success: function(msg) {
					lightbox(id);
				}
			});
		}
	});
}
function lightbox(id) {
	
	$("#loader").hide();
	$("#lightImg").attr('src', 'preview.jpg?id=' + id);
	

	$("#lightbox").show();
	setTimeout(function() {
		wi = $("#lightImg").width();
		hi = $("#lightImg").height();

		
		max = (wi > hi) ? wi : hi;
		max += 50;
		
		
		$("#lightImg").css({
			'margin-left': (max / 2) - (wi / 2),
			'margin-top': (max / 2) - (hi / 2)
		});
		
		$("#lightbox").css('margin-left', (max / 2 * -1) + "px")
		max += "px";
			$("#lightbox").animate({'height': max}, 300, function() {
				$("#lightbox").animate({'width': max}, 300);
			});
	}, 100);
	
}
function hideLightbox() {
	$("#lightbox").animate({'width': '5px'}, 300, function() {
		$("#lightbox").animate({'height': '0px'}, 300, function() {
			$("#overlay").fadeOut(300);
		});
	});
}
</script>

</head>
<body>
<?php include "header.php"; ?>
<div id="overlay">
<img src="images/ajax-loader.gif" id="loader" />
</div>	
<div id="lightbox">
	<img src="" id="lightImg" />
</div>

<div id="wrapper">
	<div id="header2">File List - <?php echo $group;?></div>
	<div id="logSlideHold">
		<div id="logHolder">
			<div id="log2">
			<?php
			$cur = 1;
			if(mysql_num_rows($data) < 1) { ?>
			No Files are Currently Available<br>
			<?php } else {
				while(($row = mysql_fetch_array($data)) && $cur <= 20) {
					extract($row, EXTR_PREFIX_ALL, "r");//Break array into seperate vars
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
						echo " downloaded";
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
			</div>
			<div id="logSlide"></div>
		</div>
	</div>
	<iframe id="fram"></iframe>
</div>
<?php include "footer.php"; ?>
</body>
</html>
