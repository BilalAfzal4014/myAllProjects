<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	$currentUser=$_SESSION['currentUser'];

	$result =  $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM favourites,job,company,user WHERE id=userJobId AND id=userCompanyId AND jobId=jobIdFav AND userIdFav='$currentUser'");

	$rows;
	$i = 0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] = $row;
		$i++;
	}

	if($i == 0)
		$rows = 0;

	echo json_encode(array('jobs' => $rows , 'count' =>$i));
}
