<!DOCTYPE html>
<html>
<head>
<?
require_once("connect.php");

$uid = $_SESSION['user_id'];//get User ID from SESSION
$gid = $_SESSION['gid'];
$sql = "SELECT * FROM users WHERE `id`=".$uid;

$data = mysql_query($sql);//Get user data
$ses_user = mysql_fetch_array($data);

$sql = "SELECT `name` FROM groups WHERE `id`=".$gid;

$group_d = mysql_query($sql);

$group_p = mysql_fetch_array($group_d);

$group = $group_p['name'];



?>
<title>File Uploader</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="flash/jquery.swfupload.js"></script>
<script type="text/javascript" src="flash/swfupload.js"></script>
<script type="text/javascript">
noFlash = false;
flashName = "";
var curFile = 0;//don't think this is used...
$(document).ready(function() {

	$(".menuClose").mouseenter(function() {
		$(this).css('background-color', '#112233');
	});

	$(".menuClose").mouseleave(function() {
		$(this).css('background-color', '#172E45');
	});
	
	$(".menuClose").click(function() {
		$("#menu").slideUp(300);
	});
	
	if($.browser.msie) {
		handleIE();
	} else {
		$("#swfupload-control").swfupload({
			upload_url: "upload.php",
			file_post_name: "uploadfile",
			file_size_limit : "4096 MB",//4GB
			flash_url : "flash/swfupload.swf",
			button_width: 75,
			button_height: 25,
			button_placeholder : $("#button")[0],
			button_image_url : "flash/button.png",
			swfupload_load_failed_handler : swfUploadLoadFailed,
			debug: false//what does this do?
		});
	}
	$("#swfupload-control").bind('fileQueued', listFile); 

	$("#swfupload-control").bind('fileQueueError', function(event, file, errorCode, message) {
		alert("Size of the file " + file.name + " is greater than the limit (4GB");
	});
	
	$("#swfupload-control").bind('fileDialogComplete', function(event, numFilesSelected, numFil) {
		//Should handle this somehow
	});

	$("#swfupload-control").bind('uploadProgress', function(event, file, bytesLoaded) {

		//I played around with some values and it looks like this progress bar isn't
		//perfectly "true", but it seems to be very close.  One way or another, it 
		//finishes when it says it finishes - the middle part is just approximate.

		var id = file.id;
		var name = file.name;
		var percent = bytesLoaded / file.size * 100;

		var text = name + " --- " + percent + "%"; //text the user sees

		percent = percent + "%";
		$("#" + file.id).children(".progress").stop();//Stop current animations.  Otherwise it takes FOREVER
		var width = $("#" + file.id).children(".progress").css('width');//Why do I grab this?  Old debugging?
		$("#" + file.id).children(".progress").animate({'width': percent}, 50);
	});
	
	$("#swfupload-control").bind('uploadComplete', function(event, file) {
			$("#" + file.id).children(".progress").stop();
			$("#" + file.id).children(".progress").animate({'width': '100%'}, 50);//Finish animation
			$("#" + file.id).slideUp(100, function() {//hide it
				$(this).remove();//remove it
				
				if($("#log").html() == "")//If #log is empty
					$("#log").html("No Files are Selected");

			sendData(file.name, file.size);//AJAXify this file upload
				setTimeout(function() { $("#swfupload-control").swfupload('startUpload'); }, 300);//go again
			});
		});
	$("#swfupload-control").bind('uploadSuccess', function(del, msg, err) {
		console.log(msg);
	});	
});
function listFile(event, file) {
	var id = file.id;
	var name = file.name;
	var gb = 1073741824;//1GB in bytes
	var mb = 1048576;//1MB in bytes
	var kb = 1024;//1KB in bytes

	if(file.size >= gb) {//Always get the right file size
		var size = (Math.round((file.size/gb) * 100)) / 100;
		var txtSize = size + "GB";
	} else if(file.size >=mb) {
		var size = (Math.round((file.size/mb) * 100)) / 100;
		var txtSize = size + "MB";
	} else {
		var size = (Math.round((file.size/kb)  * 100)) / 100;
		var txtSize = size + "KB";
	}	

	
	if($("#log").html() == "No Files are Selected")
		$("#log").html("");//if #log is empty

	var html = "<div id='" + id + "' class='logItem' style='height: 15px;'><div class='progress'></div><span class='logLabel'>" + name + " --- " + txtSize + "</span>";
	$("#log").append(html);//Maybe I should slideDown this
}

function sendData(file, size) {
	var custom = $("#customContact").attr("checked");
	var name = $("#name").val();
	var phone = $("#phone").val();
	var email = $("#email").val();
	var details = $("#details").val();
	var os = $("#os").val();
	var revision = $("#revision").attr("checked") ? 1 : 0;	
	var user = <?=$ses_user['id'];?>;
	var group = <?=$ses_user['group_id'];?>; //grab data to send via AJAX

	$.ajax({
		type: "POST",
		url: "ajaxUpload.php",
		data: {"name": name, "email": email, "phone": phone, "user": user,
			 "group": group, "detail": details, "os": os, "revision": revision,
			 "file": file, "size": size },
		success: function(msg) { console.log(msg); },//debug.  Needs new handle.
		error: function(msg) { console.log(msg); } //debug.  Needs new handle.
	});

}

function startUpload() {
	if(noFlash) {
		sendData(flashName, 0)
		$("#menu").slideUp(300, function() {
			document.location.reload();
		});	
	} else {	
		$("#menu").slideUp(300, function() {
			$("#swfupload-control").swfupload('startUpload');//Go!
		});
	}
}

function customContactToggle() {
	if($("#customContact").attr('checked')) {//If we want custom contacts
		$("#menuContact").children("input").attr('disabled', '');
	} else {
		$("#menuContact").children("input:not(#customContact)").attr('disabled', 'disabled');
	}
}

function prepNoFlashData() {
	noFlash = true;

	flashName = $("#uploadFile").val().replace(/\\/, "\\\\").replace(/^.*\\/, '');

	$("#menu").slideDown(300);	
}



function swfUploadLoadFailed() {
	$("#button").remove();
	$("#button2").remove();
	$("#swfupload-control").remove();
	$("#log").remove();

	html = "It appears that you have a problem with your flash.  You may need to";
	html += "<a href='http://www.adobe.com/software/flash/about'>download flash.</a>";
	html += "  If that doesn't work, there are some more ";
	html += "<a href='http://kb2.adobe.com/cps/191/tn_19166.html'>troubleshooting tips</a>";
	html += " available<br>";
	html += "<form action='upload.php' enctype='multipart/form-data' target='fram' method='POST'>";
	html += "<input type='file' id='uploadFile' name='uploadfile' />";
	html += "<br><button class='button' onClick='prepNoFlashData();'>Upload</button>";
	html += "</form><iframe id='fram' name='fram'></iframe>";

	$("#wrapper").append(html);

	noFlash = true;
}
function handleIE() {
	$("#swfupload-control").swfupload({
			upload_url: "upload.php",
			file_post_name: "uploadfile",
			file_size_limit : "5000 MB",//4GB
			file_upload_limit: 10,//Don't really like this
			flash_url : "flash/swfupload.swf",
			button_width: 60,
			button_height: 25,
			button_placeholder : $("#button")[0],
			button_image_url : "flash/button.png",
			swfupload_load_failed_handler : swfUploadLoadFailed,
			debug: false });

	$("button").css({
		'font-size': '11px',
		'padding-top': '1px',
		'padding-bottom': '4px',
		'margin-left': '5px'});

	setTimeout(function() { $(".swfupload").height('20px'); }, 100);
}
function showMenu() {
	$("#menuDetails").children("input").val("");
	$("#details").val("");
	$("#menuDetails").css('margin-left', '0px');	

	$("#menu").slideDown(300);
}
</script>

</head>
<body>
<? include "header.php"; ?>
<div id="wrapper">
	<div id="menu">
		<div id="menuContents">
			<div class="menuItem" id="menuDetails">
				<span class="menuHeader">Upload Details</span>
				<span class="menuClose">X</span><br>
				Batch Details:<br>
				<textarea id="details"></textarea><br>
				Operating System: <select id="os"> 
					<option value="mac">Mac</option> 
					<option selected="selected" value="pc">PC</option> 
					<option value="linux">Linux</option> 
				</select><br>Revised File: 
				<input type="checkbox" id="revision"><br>
				<span id="current">1/2</span><br>
				<span onclick="$('#menuDetails').animate({'margin-left': '-500px'}, 500);" id="menuNext">Next -></span><br>
			</div>
			<div class="menuItem" id="menuContact">
				<span class="menuHeader">Contact Info</span>
				<span class="menuClose">X</span><br>
				Use custom contact info <input type="checkbox" id="customContact" onClick="customContactToggle();" /><br>
				Name: <input disabled="disabled" type="text" id="name" name="name" value="<?=$ses_user['name'];?>" /><br>
				Email Address: <input disabled="disabled" type="text" id="email" name="email" value="<?=$ses_user['email'];?>" /><br>
				Phone Number: <input disabled="disabled" type="text" id="phone" name="phone" value="<?=$ses_user['phone'];?>" /><br>
				<span id="current">2/2</span><br>
				<span onClick="startUpload();" id="menuNext">Upload Files -></span><br>
			</div>
		</div>
	</div>
	<div id="header">File Upload - <?=$group;?></div>
	<input type="button" id="button" />
	<button id="button2" style="margin-bottom: 10px;" onClick="showMenu();">Upload</button>

	<div id="swfupload-control"></div>
	<div id="log">No Files are Selected</div>
</div>
</body>
</html>
