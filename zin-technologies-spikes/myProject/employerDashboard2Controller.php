<?php



$currentUser=$_SESSION['currentUser'];
$result = $mysqli->query("SELECT jobId,jobDescription,salary FROM job WHERE userJobId='$currentUser'");

$rows;
$i=0;
while($row = $result->fetch_array(MYSQLI_NUM))
{
	$rows[$i] =$row;
	$i++;
}
$active = 2;
//$_SESSION['yourJobs']=$rows;
//$_SESSION['totalJobs']=$i;

//header('Location: employerDashboard2.php');




