<?php
include ('AjaxProtection.php');
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	$id = $_GET['id'];
	$id2 = $_GET['id2'];

	$mysqli->query("UPDATE cv SET cvStatus='1' WHERE fileName='$id' AND cvEmployeeId='$currentUser'");	

	if($id2 != -1)
	{
		$mysqli->query("UPDATE cv SET cvStatus='0' WHERE fileName='$id2' AND cvEmployeeId='$currentUser'");		
	}


	echo json_encode(1);
}