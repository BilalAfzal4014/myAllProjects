<?php
include ('AjaxProtection.php');


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	$id = $_GET['id'];
	
	if(file_exists("cvs/".basename($id)))  // waisy is check ki zaroorat nahe hai kion k hum url sy aani wali call ko redirect karein gy sirf ajax call he aayey gi jis mein filename bilkul theek aye ga hamesha
	{	
		$result = $mysqli->query("SELECT cvStatus FROM cv WHERE fileName='$id'");

		$cvStatus = $result->fetch_row();

		unlink("cvs/".basename($id));
		$mysqli->query("DELETE  FROM cv WHERE fileName='$id'"); 
	}
	if($cvStatus[0]==1)	
		echo json_encode(1);
	else
		echo json_encode(0);
}