<?php

include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{


	$id=$_GET['id'];

	$result = $mysqli->query("SELECT name,email,id,status FROM user,job,Application WHERE jobApplicationId='$id' AND id=userApplicationJobId AND jobApplicationId=jobId");


	$rows;
	$i=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}

	$j=0;
	while($j < $i)
	{
		$person=$rows[$j][2];

		if($rows[$j][3] == 0)
			$mysqli->query("UPDATE application SET status='1' WHERE userApplicationJobId='$person' AND jobApplicationId='$id'");
		$j++;

	}	


	$result = $mysqli->query("SELECT name,email,id,status FROM user,job,Application WHERE jobApplicationId='$id' AND id=userApplicationJobId AND jobApplicationId=jobId");


	$i=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}



	$result = $mysqli->query("SELECT jobDescription,salary FROM job WHERE jobId='$id'");
	$job = $result->fetch_row();

	if($i>0)
		echo json_encode(array("records" => $rows , "count" => $i , "jobDetails" => $job));
	else
		echo json_encode(array("count" => $i));
}