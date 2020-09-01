<?php
include ('db.php');

session_start();


if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser']))
{
	$currentUser=$_SESSION['currentUser'];
	$result = $mysqli->query("SELECT type FROM user WHERE id='$currentUser'");
	$row = $result->fetch_row();

	if($row[0]=="employer")
		header('Location: employerDashboard1.php');
}
else
{
	header('Location: logIn.php');	
}