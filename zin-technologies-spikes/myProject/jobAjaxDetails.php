<?php

include ('AjaxProtection.php');


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{


	$id=$_GET['id'];
	$result = $mysqli->query("SELECT jobDescription,salary,jobDescriptionReal,jobType FROM job WHERE jobId='$id'");
	$jobDetails = $result->fetch_row();

	echo json_encode(array("details" => $jobDetails));
}



