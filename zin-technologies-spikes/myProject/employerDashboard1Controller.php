<?php



$currentUser=$_SESSION['currentUser'];
$result = $mysqli->query("SELECT name FROM user WHERE id='$currentUser'");
$row = $result->fetch_row();
//echo $row[0];
$_SESSION['name']=$row;


$result = $mysqli->query("SELECT imageName FROM userimages WHERE userIdImage='$currentUser'");
$row = $result->fetch_row();
$_SESSION['image']=$row[0];
$active = 1;
//echo $_SESSION['name'][0]; 


//header('Location: employerDashboard1.php');




