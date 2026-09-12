<?php
session_start();
require_once("includes/config.php");
if(!empty($_POST["email"])) {
	$email= $_POST["email"];
	if (filter_var($email,FILTER_VALIDATE_EMAIL)==false) {

		echo "<span style='color:red;font: size 15px;'>Enter a valid email!</span>";

	}
	else {
		$result ="SELECT count(*) FROM userregistration WHERE email=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$email);
		$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0)
{
echo "<span style='color:red'> Email already exist .</span>";
}
else{
	echo "<span style='color:green'> Email available for registration .</span>";
}
}
}

if(!empty($_POST["mkpt"])) {
	$mkpt= $_POST["mkpt"];
	$result ="SELECT count(*) FROM student WHERE mkpt=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$mkpt);
		$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
if($count>0)
{
echo "<span style='color:red'> MKPT already exist .</span>";
}
else{
	echo "<span style='color:green'> MKPT available for registration .</span>";
}
}


if(!empty($_POST["oldpassword"])) 
{
$uid = isset($_SESSION['id']) ? $_SESSION['id'] : '';
if ($uid == '') {
echo "<span style='color:red'> Not authenticated</span>";
} else {
$pass=$_POST["oldpassword"];
$result ="SELECT password FROM userregistration WHERE userID=? AND password=?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('is',$uid,$pass);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows>0) 
echo "<span style='color:green'> Password  matched .</span>";
else echo "<span style='color:red'> Password Not matched</span>";
$stmt->close();
}
}

