<!DOCTYPE html>

<html>
<head>
<?
require_once("connect.php");

	if(isset($_SESSION['page']))
		$page = $_SESSION['page'];
	else
		$page = 1;


	
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
?>
<title>File List</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$("#overlay").click(hideLightbox);

	$(".nobubble").click(function(event) {
		event.stopImmediatePropagation();//Nested clickables should not "click" the parent
	});

	$(".logItem").each(function() {
		$.data(this, "show", "false");//Data is not slid down
	});
	
	$(".logItem").mouseenter(function() {
			$(this).css('background-color', '#112255');
	});		

	$(".logItem").mouseleave(function() {
		$(this).css('background-color', '');
	});

	$(".logItem").click(function() {
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
	}, 50);
	
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
<? include "header.php"; ?>
<div id="overlay">
<img src="images/ajax-loader.gif" id="loader" />
</div>	
<div id="lightbox">
	<img src="" id="lightImg" />
</div>

<div id="wrapper">
	<div id="header2">File List</div>
	<div id="logSlideHold">
		<div id="logHolder">
			<div id="log2">
			<? 
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
			<? $cur++;	}
			}
			if($offset > 0) 
				echo "<a style='margin-right: 5px' class='spanLink nobubble' href='javascript:newPage(-1);'><--</a>";
			echo $page." / ".(ceil($num / 20));
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
</body>
</html>
