<?php

include ('unRegisteredAjaxProtection.php'); 

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	
	$title=$_GET['id'];

	$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId AND jobDescription LIKE '%$title%'");
	
	$rows;
	$i=0;
	
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}

	if($i==0)
		$rows=0;


	echo json_encode(array("records" => $rows , "count" => $i));
}
