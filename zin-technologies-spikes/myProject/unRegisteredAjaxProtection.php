<?php

include ('db.php'); 
session_start();


if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser']))
{
	$result = $mysqli->query("SELECT type FROM user WHERE id='$currentUser'");
	$row = $result->fetch_row();

	if($row[0]=="employee" && (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')))
		header('Location: employeeDashboard1.php');

	if($row[0]=="employer" && (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')))
		header('Location: employerDashboard1Controller.php');
}

if(!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	header('Location: logIn.php');		
}