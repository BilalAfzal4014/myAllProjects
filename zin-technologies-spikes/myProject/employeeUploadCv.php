<?php
include ('AjaxProtection.php');


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	
	$totalFiles=count($_FILES['cv']['name']);

	for($j = 0; $j < $totalFiles; $j++)
	{	

		$fileName = $_FILES['cv']['name'][$j];
		$fileTmp = $_FILES['cv']['tmp_name'][$j];
		$title = $_POST['title'][$j];
		if(strlen($fileName)!=0)
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
		
			$uniqueName=$string.$fileName;
			$targetPath = "cvs/";
			$targetPath = $targetPath.basename($uniqueName); 
			move_uploaded_file($fileTmp,$targetPath);
			
			$mysqli->query("INSERT INTO cv (cvEmployeeId,fileName,cvStatus,originalFileName,Title) VALUES ('$currentUser', '$uniqueName', '0','$fileName','$title')");

		}
	}	
	$result = $mysqli->query("SELECT fileName,originalFileName,cvStatus,Title FROM cv WHERE cvEmployeeId='$currentUser'");

	$rows;
	$i=0;

	while($row = $result->fetch_array(MYSQLI_NUM))
	{
		$rows[$i] =$row;
		$i++;
	}
	echo json_encode(array("cvs" => $rows , "count" => $i));

}