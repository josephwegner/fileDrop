<?php require_once("config/connect.php"); adminPage(); ?>
<!DOCTYPE html>

<html>
<head>
<?php


$sql = "SELECT * FROM groups";//get ALL groups

$data = mysql_query($sql);

?>
<title>Group Management</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="config/infoBox.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    var aboutUs = new infoBox("aboutus", "http://www.wegnerdesign.com", [ "fRight" ]);
	
	$(".menuClose").mouseenter(function() {
		$(this).css('background-color', '#112233');
	});

	$(".menuClose").mouseleave(function() {
		$(this).css('background-color', '#172E45');
	});
	
	$(".menuClose").click(function() {
		$("#menu").slideUp(300);
	});
	
});
function register() {
	var group_name = $("#group_name").val();//Group Vars
	var open_register = $("#open_register").attr('checked') ? 1 : 0;

	var user = $("#user").val();//User Vars
	var pass = $("#password").val();
	var pass2 = $("#password2").val();
	var name = $("#name").val();
	var email = $("#email").val();
	var phone= $("#phone").val();

	//Validation here//

	//End Validation//

	$.ajax({
		type: "POST", 
		url: "ajaxGroup.php",
		data: {"g_name": group_name, "open_register": open_register, "user": user, "pass": pass,
			"a_name": name, "email": email, "phone": phone },
		success: function(msg) {
				$("#menu").slideUp(500);
			},
		error: function(err, err2, err3) {
				alert(err3);
			}
	});
}

function startCreate() {
	$("#menuGroup").children("input").val("");
	$("#menuContact").children("input").val("");

	$("#menuGroup").css('margin-left', '0px');
	
	$("#menu").slideDown(300);
}
</script>

</head>
<body>
<?php include "header.php"; ?>
<div id="wrapper">
	<div id="menu">
		<div id="menuContents">
			<div class="menuItem" id="menuGroup">
				<span class="menuHeader">Group Details</span>
				<span class="menuClose">X</span><br>
				Group Name: 
				<input type="text" id="group_name" style="margin-top: 100px; margin-bottom: 30px;" /><br>
				Open Registration:
				<input type="checkbox" id="open_register"><br>
				<span id="current">1/2</span><br>
				<span onclick="$('#menuGroup').animate({'margin-left': '-500px'}, 500);" id="menuNext">Next -></span><br>
			</div>
			<div class="menuItem" id="menuContact">
				<span class="menuHeader">Admin Info</span>
				<span class="menuClose">X</span><br>
				<div style="width: 80%; margin-top: 80px;" class="alignRight">
					Username: <input type="text" id="user" name="user" /><br>
					Password: <input type="password" id="password" name="password" /><br>
					Verify Password: <input type="password" id="password2" name="password2" /><br>
					Name: <input type="text" id="name" name="name" /><br>
					Email Address: <input type="text" id="email" name="email" /><br>
					Phone Number: <input type="text" id="phone" name="phone" /><br>
					<span id="current">2/2</span><br>
				</div>
				<span onClick="register();" id="menuNext">Create Group -></span><br>
			</div>
		</div>
	</div>
	<div id="header2">Groups</div>
	<button style="margin-top: 10px; margin-bottom: 5px;" onClick="startCreate();">New</button>
	<div class="clearboth"></div>
	<div id="log2">
	<?php if(mysql_num_rows($data) < 1) { ?>
		No groups have been created.
	<?php } else {
		while($row = mysql_fetch_array($data)) {
			extract($row, EXTR_PREFIX_ALL, "r");//break into vars
			$open = ($r_open_register) ? "Yes" : "No";
	?>
		<div class="logItem">
			<div class="groupDetail">Name: <?php echo $r_name;?></div>
			<div class="groupDetail">Code: <?php echo $r_code;?></div>
			<div class="groupDetail">Open Registration: <?php echo $open;?></div>
		</div>
	<?php
		}
	   }
	?>
	</div>
</div>
<?php include "footer.php"; ?>
</body>
</html>
