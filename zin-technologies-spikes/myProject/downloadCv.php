<?php
include ('db.php'); 
session_start();

if((!( isset($_SESSION['currentUser'])  &&  !empty($_SESSION['currentUser'])  )) || empty($_SERVER['HTTP_REFERER']))
{	
	// note : agar hum browser per url type kar k hit krein tou uska HTTP_REFERER empty hota hai jab k action perform kar k i.e. koi click event kar k karein tou phir empty nahe hota phir pichlay page ka url hota hai HTTP_REFERER k pas 
	header('Location: logIn.php');

}

$currentUser=$_SESSION['currentUser'];

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = $_GET['id'];
	
	if(file_exists("cvs/".basename($id)))
	{	
		header('Content-Description: File Transfer');   //its not a ajax request and i didnt write redirect back logic but there is some line in headers which forcing it to behave like redirect back after file is downloading
		header('Content-Type: application/force-download');
		header("Content-Disposition: attachment; filename=\"" . basename($id) . "\";");
		header('Content-Transfer-Encoding: binary');	
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($id));
		ob_clean();
		//flush();
		readfile("cvs/".$id); //showing the path to the server where the file is to be download
		exit;
	}
	else
	{
		header('Location: logIn.php');			
	}
}
else
{
	header('Location: logIn.php');

	//header('Location: ' . $_SERVER['HTTP_REFERER']);  // redirecting back url

}