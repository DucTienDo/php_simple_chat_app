<style type="text/css" media="screen">
	body {
		text-align: center;
		font-size: 12pt;
	}
	.error-report {
		color: red;
		padding-top: 20px;
		padding-bottom: 20px;
		background-color: yellow;
	}
	.label-ex {
		margin-right: 3px;
		width: 150px;
		display: inline-block;
		text-align: right;
		}
	.form {
		background-color: lightgray;
		display: inline-block;
		padding: 5px 0px 5px 0px;
	}
	.label-align {
		
	}
	.success-report {
		color: green;
		padding-top: 20px;
		padding-bottom: 20px;
		background-color: yellow;
	}
	.optional {
		font-size: 10pt;
		color: #0000f0;
	}
	.block-500px {
		width: 470px;
		display: block;
		text-align: left;
	}
</style>
<html>
	<head>
		<title>SCS's Homepage</title>
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<h1 style="font-family: Oswald;">Simple Chat System</h1>
		<div>
			<form method="POST">			<!--sign in form-->
			<div class="form">
				<div class="block-500px">
					<label class="label-ex" for="usr">Username</label>
					<input type="text" name="usr" placeholder="Username"/>
					<label class="optional" for="anonymous">As anonymous?</label>
					<input type="checkbox" name="anonymous" value="1" style="vertical-align: middle">
					<br/>
				</div>
				<div class="block-500px">
					<label class="label-ex" for="pwd">Password</label>
					<input type="password" name="pwd" placeholder="Password">
					<br/>
				</div>
				<div>
					<label class="optional" for="rmb">Remember me?</label>
					<input type="checkbox" name="rmb" value="1" style="vertical-align: middle">
					<br/>
				</div>
				<input type="submit" name="login" value="Login"> <input type="submit" name="signup" value="Sign Up">
			</div>
		</form>
	</div>
</body>
</html>
<?php
$file_usr = "user_list.csv";
session_start();
			function successProcessing () {			// goto login page
	echo '<p class="success-report">Login successful!</p>';
	header('Location: ./chat.php');
//echo '<script> window.location.replace("./chat.php");</script>';
}
function isMatch ($usr, $pwd) {			// check username & password
global $file_usr;
$usrs = file_get_contents($file_usr);
$usrs = explode(PHP_EOL, $usrs);
$nUsr = sizeof($usrs);
$key = strtolower(trim($usr)) . ',' . md5(trim($pwd));
for ($i=0; $i<$nUsr; ++$i) {
if ($key === trim(strtolower($usrs[$i])))
return true;
}
return false;
}
function isExist ($usr) {			// check username existance
global $file_usr;
$usrs = file_get_contents($file_usr);
$usrs = explode(PHP_EOL, $usrs);
$nUsrs = sizeof($usrs);
$usr = strtolower(trim($usr));
$nUsr = strlen($usr);
for ($i=0; $i<$nUsrs; ++$i) {
if ($usr === strtolower(substr(trim($usrs[$i]), 0, $nUsr)))
return true;
}
return false;
}
// auto log in (for remember checkbox)
if (isset($_SESSION["usr"]) && isset($_SESSION["pwd"]) && isMatch($_SESSION["usr"], $_SESSION["pwd"])) {
successProcessing();
}
// Show sign up form & create account when Sign up button clicked
if (!empty($_POST['signup']) || (isset($_POST['newacc']) && !empty($_POST['newacc']))) {
//sign up..
echo '<div>
<form method="POST">
	<span class="form">
		<div class="block-500px" style="">
			<label class="label-ex" for="newusr">Username</label>
			<input type="text" name="newusr" placeholder="Username" required/>
		</div>
		<div class="block-500px">
			<label class="label-ex" for="newpwd">Password</label>
			<input type="password" name="newpwd" placeholder="Password" required><br/>
			<label class="label-ex" for="renewpwd">Retype Password</label>
			<input type="password" name="renewpwd" placeholder=" Retype password" required>
		</div>
		<input type="submit" value="Create Account" name="newacc">
	</span>
</form>
</div>';
// create account
if (!empty($_POST['newacc'])) {
// check availability
if (!isExist($_POST['newusr'])) {
if ($_POST['renewpwd'] === $_POST['newpwd']) {
file_put_contents($file_usr, trim($_POST['newusr']) . ',' . md5(trim($_POST['newpwd'])) . PHP_EOL, FILE_APPEND);
echo '<p class="success-report">Account has been created</p>';
}
else {
echo '<p class="error-report">Retype password does not match</p>';
}
}
else {
echo '<p class="error-report">Username is unavailable</p>';
}
}
}
elseif (!empty($_POST["login"])) {	// proccess login when Log in button clicked
if (!empty($_POST["usr"])) {
// allow anonymous user log in without password
if (isset($_POST["anonymous"]) && !empty($_POST["anonymous"])) {
$_SESSION["success"] = false;
$_SESSION["anonymous"] = true;
$_SESSION["usr"] = $_POST["usr"].' (Anonymous)';
successProcessing();
}
// check correctness of usernamw & password
if (isMatch($_POST['usr'], $_POST['pwd'])) {
$_SESSION["usr"] = $_POST["usr"];
$_SESSION['success'] = true;
// for auto log in (remember checkbox)
if (isset($_POST["rmb"]) && $_POST["rmb"] === "1"){
$_SESSION["pwd"] = $_POST["pwd"];
}
else $_SESSION["pwd"] = null;
successProcessing();
}
else {
$_SESSION['success'] = false;
echo '<p class="error-report">Username or password is incorrect</p>';
}
}
}
?>