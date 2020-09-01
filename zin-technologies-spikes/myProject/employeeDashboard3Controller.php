<?php


$currentUser=$_SESSION['currentUser'];

$result = $mysqli->query("SELECT fileName,originalFileName,cvStatus,Title FROM cv WHERE cvEmployeeId='$currentUser'");

$rows;
$i=0;

while($row = $result->fetch_array(MYSQLI_NUM))
{
	$rows[$i] =$row;
	$i++;
}
$active = 3;
//$_SESSION['cvs']=$rows;
//$_SESSION['totalCvs']=$i;


//cvStatus,originalFileName
//header('Location: employeeDashboard3.php');



