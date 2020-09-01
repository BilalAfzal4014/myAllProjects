<?php
include ('AjaxProtection.php');


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{

	$name=$_POST['nameName'];
	/////////////////////////////////////

	$imageName = $_FILES['imageName']['name'];

	$imageTmp = $_FILES['imageName']['tmp_name'];

	if(strlen($imageName) != 0)
	{	
		// generating some random string
	
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$string = '';
 		$max = strlen($characters) - 1;
 		for ($i = 0; $i < 8; $i++) 
 		{
      		$string .= $characters[mt_rand(0, $max)];
 		}

 		// finish generating some random string
		
		$uniqueName=$string.$imageName;
		$targetPath = "images/";
		$targetPath = $targetPath.basename($uniqueName); 
		move_uploaded_file($imageTmp,$targetPath);
		

		$result = $mysqli->query("SELECT imageName FROM userimages WHERE userIdImage='$currentUser'");
		$row = $result->fetch_row();	
		
		if(strlen($row[0]) == 0)
		{
			$mysqli->query("INSERT INTO userimages (userIdImage,imageName) VALUES ('$currentUser', '$uniqueName')");
		}
		else
		{
			unlink("images/".basename($row[0]));
			$mysqli->query("UPDATE userimages SET imageName='$uniqueName' WHERE userIdImage='$currentUser'");
		}
		$_SESSION['image'] = $uniqueName;
	}
	else
	{
		$uniqueName = 0;
	}	



	//////////////////////////////////////
	
	$mysqli->query("UPDATE user SET name='$name' WHERE id='$currentUser'");


	$result = $mysqli->query("SELECT name FROM user WHERE id='$currentUser'");
	$row = $result->fetch_row();
	
	$_SESSION['name']=$row;


	echo json_encode(array("tip" => $uniqueName));
}