<?php



$currentUser=$_SESSION['currentUser'];
$result = $mysqli->query("SELECT name,email,type,companyName FROM user,company WHERE usercompanyId=id AND id='$currentUser'");

$row=$result->fetch_row();
$active = 3;
//$_SESSION['profileDetails']=$row;

//header('Location: employerDashboard3.php');



