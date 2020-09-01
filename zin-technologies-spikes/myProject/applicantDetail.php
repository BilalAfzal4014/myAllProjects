<?php

include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{


	$pId=$_GET['pId'];
	$jId=$_GET['jId'];

	//$result = $mysqli->query("SELECT name,email,jobLetter,originalFileName,FileName FROM user, application, userjobcv, cv WHERE id=userApplicationJobId AND userApplicationJobId=userCvApplicationJobId AND jobApplicationId=jobCvApplicationId AND fileNameJob=fileName AND userApplicationJobId='$pId' AND jobApplicationId='$jId' AND cvEmployeeId='$pId'");
//	$record = $result->fetch_row();	// i have to remove this query and have to done it in two queries bcz if i do in this query then i applicant with no cv will return o rows although he has applied for job

	$result = $mysqli->query("SELECT name,email,jobLetter FROM user, application WHERE id=userApplicationJobId AND userApplicationJobId='$pId' AND jobApplicationId='$jId'");
	$record = $result->fetch_row();


	$result = $mysqli->query("SELECT originalFileName,fileName,Title FROM cv,userjobcv WHERE jobCvApplicationId='$jId' AND userCvApplicationJobId='$pId' AND userCvApplicationJobId=cvEmployeeId AND fileName=fileNameJob");
	$cvJob = $result->fetch_row();

	if(strlen($cvJob[0]) == 0)
		$cvJob[0]=0;

	$result = $mysqli->query("SELECT imageName FROM user,userimages WHERE id=userIdImage AND id='$pId'");

	$image = $result->fetch_row();

	if(strlen($image[0]) == 0)
	{
		$image[0] = "";		
	}


	if(strlen($record[0]) != 0)
		echo json_encode(array("details" => $record , "cv" => $cvJob, "image" => $image[0] ,"tip" => 1));
	else
		echo json_encode(array("details" => $record , "cv" => $cvJob, "image" => $image[0] , "tip" => 0));		
	
}