<?php


$currentUser=$_SESSION['currentUser'];
$result = $mysqli->query("SELECT name FROM user WHERE id='$currentUser'");
$row = $result->fetch_row();
$_SESSION['name']=$row;


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
$result = $mysqli->query("SELECT imageName FROM userimages WHERE userIdImage='$currentUser'");
$row = $result->fetch_row();
$_SESSION['image']=$row[0];
$active = 1;
//$_SESSION['hasApplied']=$rows2;
//$_SESSION['hasCount']=$i2;

//$_SESSION['jobs']=$rows;
//$_SESSION['count']=$i;













/*

SELECT jobId,jobDescription,salary,companyName ,userIdFav
FROM (user,company,job) LEFT JOIN favourites ON userIdFav=7 AND jobIdFav=jobId
WHERE id=userCompanyId AND id=userJobId AND NOT EXISTS
( 
    SELECT * 
    FROM application 
    WHERE jobApplicationId=jobId AND userApplicationJobId=7 
)

*/

