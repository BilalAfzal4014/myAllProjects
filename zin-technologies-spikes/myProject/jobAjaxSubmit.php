<?php

include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$jobDescription=$_POST['jobb'];
	$salary=$_POST['salaryy'];
	$jobDescriptionReal=$_POST['jobDescription'];
	$type=$_POST['jobTypee'];
	$mysqli->query("INSERT INTO job (jobDescription,salary,jobDescriptionReal,jobType,UserJobId) VALUES ('$jobDescription','$salary','$jobDescriptionReal','$type', '$currentUser')");

	echo json_encode(array("tip" => 1));
}



