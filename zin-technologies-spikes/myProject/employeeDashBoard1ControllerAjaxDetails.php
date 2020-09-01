<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$id=$_GET['id'];
	$result = $mysqli->query("SELECT jobDescription,salary,jobDescriptionReal,jobType FROM job WHERE jobId='$id'");
	$jobDetails = $result->fetch_row();

	
	// current user ki cvs

	$result = $mysqli->query("SELECT fileName,originalFileName,Title FROM cv WHERE cvEmployeeId='$currentUser' order by cvStatus DESC");

	$cvDetails;
	$i=0;

	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$cvDetails[$i] = $row;
		$i++;
	}

	if($i == 0)
		$cvDetails=0;

	$result = $mysqli->query("SELECT imageName FROM job,user,userimages WHERE userJobId=id AND id=userIdImage AND jobId='$id'");

	$image = $result->fetch_row();

	if(strlen($image[0]) == 0)
	{
		$image[0] = "";		
	}

	echo json_encode(array("details" => $jobDetails, "cvDetails" => $cvDetails, "count" => $i, "image" => $image[0]));
}
