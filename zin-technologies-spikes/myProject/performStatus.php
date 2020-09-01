<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$pId = $_POST['personIdName'];
	$jId = $_POST['jobIdName'];
	$statusId = $_POST['statusSelectionName'];
	



	$result = $mysqli->query("SELECT jobLetter FROM application WHERE userApplicationJobId='$pId' AND jobApplicationId='$jId'");
	$record = $result->fetch_row();

	if(strlen($record[0]) != 0)
	{
		if($statusId != 5)
		{	
			if($statusId != 4)
			{	
				$result = $mysqli->query("UPDATE application SET status='$statusId' WHERE userApplicationJobId='$pId' AND jobApplicationId='$jId'");
			
				echo json_encode(array("tip" => 1, "status" => $statusId));
			}
			else
			{
				$result = $mysqli->query("UPDATE application SET status='1' WHERE userApplicationJobId='$pId' AND jobApplicationId='$jId'");
				
				echo json_encode(array("tip" => 1, "status" => 1));
			}
		}
		else
		{
			$mysqli->query("DELETE  FROM userjobcv WHERE userCvApplicationJobId='$pId' AND jobCvApplicationId='$jId'"); 

			$mysqli->query("DELETE  FROM application WHERE userApplicationJobId='$pId' AND jobApplicationId='$jId'"); 

			echo json_encode(array("tip" => 0));
		}
	}
	else
	{
		echo json_encode(array("tip" => 0));	
	}
	

}