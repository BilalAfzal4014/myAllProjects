<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$id=$_GET['id'];
	$result = $mysqli->query("SELECT jobDescription,salary,jobDescriptionReal,jobType FROM job WHERE jobId='$id'");
	$jobDetails = $result->fetch_row();

	$result = $mysqli->query("SELECT jobLetter,status FROM application WHERE jobApplicationId='$id' AND userApplicationJobId='$currentUser'");
	$jobLetter = $result->fetch_row();

	$result = $mysqli->query("SELECT originalFileName,fileName,Title FROM cv,userjobcv WHERE jobCvApplicationId='$id' AND userCvApplicationJobId='$currentUser' AND userCvApplicationJobId=cvEmployeeId AND fileName=fileNameJob");
	$cvJob = $result->fetch_row();

	if(strlen($cvJob[0]) == 0)
		$cvJob[0]=0;

	$result = $mysqli->query("SELECT imageName FROM job,user,userimages WHERE userJobId=id AND id=userIdImage AND jobId='$id'");

	$image = $result->fetch_row();

	if(strlen($image[0]) == 0)
	{
		$image[0] = "";		
	}

	if(strlen($jobLetter[0]) != 0)
		echo json_encode(array("details" => $jobDetails,"jobLetter" => $jobLetter, "cv" => $cvJob, "image" => $image[0] ,"tip" => 1));
	else
	{	
		// this code will run when employer has cancel that paritcular job in which we have clicked, but this job was still clickable because of the fact that page was already loaded
		$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS(SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");

		$rows;
		$i=0;
		while($row = $result->fetch_array(MYSQLI_NUM))
		{
			$rows[$i] =$row;
			$i++;
		}
		echo json_encode(array("rows" => $rows, "count" => $i , "tip" => 0));
	}
}