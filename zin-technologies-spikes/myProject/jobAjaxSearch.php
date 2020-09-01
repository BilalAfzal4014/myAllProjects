<?php

include ('AjaxProtection.php');





if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{



	$title=$_GET['id'];

	if(strlen($title)!=0)
		$result = $mysqli->query("SELECT jobId,jobDescription,salary FROM job WHERE userJobId='$currentUser' AND jobDescription LIKE '%$title%'");
	else
		$result = $mysqli->query("SELECT jobId,jobDescription,salary FROM job WHERE userJobId='$currentUser'");


	$rows;
	$i=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}

	if($i==0)
		$rows=1;

	echo json_encode(array("rows"=>$rows ,"count"=>$i));



}