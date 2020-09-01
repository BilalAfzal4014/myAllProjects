<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	//jobDescription like '%' + '$title' + '%'
	//jobDescription='$title'
	$title=$_GET['id'];

	if(strlen($title)!=0)
	{	//$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId AND jobDescription='$title' AND NOT EXISTS(SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");
		$result = $mysqli->query("SELECT jobId, jobDescription, salary, companyName, userIdFav FROM (user,company,job) LEFT JOIN favourites ON userIdFav='$currentUser' AND jobIdFav=jobId WHERE id=userCompanyId AND id=userJobId AND jobDescription LIKE '%$title%' AND NOT EXISTS ( SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");	
	}
	else
		$result = $mysqli->query("SELECT jobId, jobDescription, salary, companyName, userIdFav FROM (user,company,job) LEFT JOIN favourites ON userIdFav='$currentUser' AND jobIdFav=jobId WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS ( SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");
			

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
