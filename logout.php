<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css"/>
</head>
<body style='text-align: center'>
<h1 style="font-family: Oswald;">Simple Chat System</h1>
<?php
session_start();
if (isset($_SESSION['success']) && (($_SESSION['success']) || (!$_SESSION['success'] && isset($_SESSION['anonymous']) && $_SESSION['anonymous']))) {
	$name = $_SESSION['usr'];
	unset($_SESSION['usr']);
	unset($_SESSION['pwd']);
	unset($_SESSION['success']);
	echo "<p>Thank you, {$name} :)<br>Arigatou!</p><p><a href='./login.php'>Go to login page.</a></p>";
}
else {
	echo "<p>You have not logged in yet. Please <a href='login.php'>click here</a> to go to login page.</p>";
}
?>
</body>
</html>