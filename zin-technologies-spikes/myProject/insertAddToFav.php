<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	$currentUser=$_SESSION['currentUser'];
	$jobId = $_GET['id'];

	$mysqli->query("INSERT INTO favourites (userIdFav,jobIdFav) VALUES ('$currentUser','$jobId')");

	echo json_encode(array('tip' => 1));
}
