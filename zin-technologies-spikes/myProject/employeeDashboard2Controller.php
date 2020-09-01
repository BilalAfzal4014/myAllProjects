<?php

$currentUser=$_SESSION['currentUser'];
$result = $mysqli->query("SELECT name,email,type FROM user WHERE id='$currentUser'");

$row=$result->fetch_row();
$active = 2;
//$_SESSION['EmployeeDetails']=$row;





