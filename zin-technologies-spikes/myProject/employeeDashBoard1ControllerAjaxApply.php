<?php
include ('AjaxProtection.php');

if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$result = $mysqli->query("SELECT name FROM user WHERE id='$currentUser'");
	$row = $result->fetch_row();
	//$_SESSION['name']=$row;
	/*$id=$_GET['id'];

	$mysqli->query("INSERT INTO application (userApplicationJobId, jobApplicationId) VALUES ('$currentUser', '$id')");


	$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS(SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");

	$rows;
	$i=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}


	$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName  FROM application,job,company,user WHERE id=userJobId AND id=userCompanyId AND jobId=jobApplicationId AND userApplicationJobId='$currentUser'");


	$rows2;
	$i2=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows2[$i2] =$row;
		$i2++;
	}


/*
for($j=0 ; $j < $i2 ; $j++)
{
echo $rows2[$j][0]." ".$rows2[$j][1]." ".$rows2[$j][2]." ".$rows2[$j][3]."<br>";	
}


//$_SESSION['hasApplied']=$rows2;
//$_SESSION['hasCount']=$i2;

//$_SESSION['jobs']=$rows;
//$_SESSION['count']=$i;



if($i==0)
	$rows=0;

if($i2==0)
	$rows2=0;


echo json_encode(array('hasApplied' => $rows2 , 'hasCount'=> $i2 , 'jobs' => $rows , 'count' =>$i));
*/







	$status=0;

	$id=$_POST['hiddenName'];
	$jobLetter=$_POST['jobLetterName'];
	$cvFileName=$_POST['cvSelectionName'];

	$mysqli->query("INSERT INTO application (userApplicationJobId, jobApplicationId, status, jobLetter) VALUES ('$currentUser', '$id', '0', '$jobLetter')");

	$mysqli->query("INSERT INTO userjobcv (userCvApplicationJobId,jobCvApplicationId,fileNameJob) VALUES ('$currentUser','$id','$cvFileName')");

	$mysqli->query("DELETE FROM favourites WHERE userIdFav='$currentUser' AND jobIdFav='$id'");

	/*
	$result = $mysqli->query("SELECT fileName FROM cv WHERE cvEmployeeId='$currentUser' AND cvStatus='1'");	

	$cv = $result->fetch_row();

	if(strlen($cv[0]) == 0)
	{	
		$result = $mysqli->query("SELECT fileName FROM cv WHERE cvEmployeeId='$currentUser' AND cvStatus='0'  LIMIT 1");
		$cv = $result->fetch_row();
	}
	
	if(strlen($cv[0]) != 0)
	{	
		
		$mysqli->query("INSERT INTO userjobcv (userCvApplicationJobId,jobCvApplicationId,fileNameJob) VALUES ('$currentUser','$id','$cv[0]')");

	}
	*/

	//$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName FROM user,job,company WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS(SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");

	$result = $mysqli->query("SELECT jobId, jobDescription, salary, companyName, userIdFav FROM (user,company,job) LEFT JOIN favourites ON userIdFav='$currentUser' AND jobIdFav=jobId WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS ( SELECT * FROM application WHERE jobApplicationId=jobId AND userApplicationJobId='$currentUser')");

	$rows;
	$i=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}


	$result = $mysqli->query("SELECT jobId,jobDescription,salary,companyName,status  FROM application,job,company,user WHERE id=userJobId AND id=userCompanyId AND jobId=jobApplicationId AND userApplicationJobId='$currentUser'");


	$rows2;
	$i2=0;
	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows2[$i2] =$row;
		$i2++;
	}


/*
for($j=0 ; $j < $i2 ; $j++)
{
echo $rows2[$j][0]." ".$rows2[$j][1]." ".$rows2[$j][2]." ".$rows2[$j][3]."<br>";	
}
*/
/*
//$_SESSION['hasApplied']=$rows2;
//$_SESSION['hasCount']=$i2;

//$_SESSION['jobs']=$rows;
//$_SESSION['count']=$i;

*/

if($i==0)
	$rows=0;

if($i2==0)
	$rows2=0;


echo json_encode(array('hasApplied' => $rows2 , 'hasCount'=> $i2 , 'jobs' => $rows , 'count' =>$i));


}
