<!DOCTYPE html>

<html>
<head>
<?
session_start();
?>
<title>File Uploader</title>
<link rel="stylesheet" type="text/css" href="main.css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {

});

function login(evt) {
	
	var user = $("#username").val();
	var pass = $("#password").val();//Get Vars

	$.ajax({
		type: "POST",
		url: "ajaxLogin.php",
		data: {"user": user, "pass": pass },
		success: function(msg) { document.location.reload(); },
		error: function(msg) { alert("Invalid Login"); } //Need to handle this better
	});
}
function startRegister() {
	$("#menuRegister").children("#input").val("");//Clear Form

	$("#menu").slideDown(500);
}
function register() {
	var user = $("#user").val();
	var pass = $("#pass").val();
	var pass2 = $("#password2").val();
	var name = $("#name").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var code = $("#code").val();//Get Vars

	//Validation Here//
		
	//End Validation//

	$.ajax({
		type: "POST",
		url: "ajaxRegister.php",
		data: {"user": user, "pass": pass, "name": name, "email": email, "phone": phone, "code": code },
		success: function(msg) {
			console.log(msg);
			$("#menu").slideUp(500);
		},
		error: function(err) {
			alert(err);//Need to handle this better
		}
	});
}	
</script>

</head>
<body>
<div id="menu">
	<div id="menuContents">
		<div class="menuItem" id="menuRegister">
			<span class="menuHeader">Admin Info</span><br>
			<div style="width: 80%; margin-top: 50px;" class="alignRight">
				Username: <input type="text" id="user" name="user" /><br>
				Password: <input type="password" id="pass" name="pass" /><br>
				Verify Password: <input type="password" id="password2" name="password2" /><br>
				Name: <input type="text" id="name" name="name" /><br>
				Email Address: <input type="text" id="email" name="email" /><br>
				Phone Number: <input type="text" id="phone" name="phone" /><br>
				Group Code: <input type="text" id="code" name="code" /><br>
				<span id="current">1/1</span><br>
			</div>	
			<span onClick="register();" id="menuNext">Register -></span><br>
		</div>
	</div>
</div>
<div id="wrapper">
	<div id="header">Login</div>
	<div id="login">
		<div class="loginHeader">Username</div>
		<input type="text" id="username" name="username" /><br>
		<div class="loginHeader">Password</div>
		<input type="password" id="password" name="password" /><br>
		<input type="submit" id="sub" name="sub" onClick="login(event);" />
	</div>
	<a id="registerLink" onClick="startRegister();">Register</a>
</div>
</body>
</html>
