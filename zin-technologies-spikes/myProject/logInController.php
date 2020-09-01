<?php
include ('db.php');  // or include 'db.php' or require 'db.php' or require ('db.php') // all are same
/*
http://php.net/manual/en/function.preg-match.php  // regular expressions link
//include('signUp.php');


//echo "hello world";

//readfile('signUp.php');
//include "signUp.php";
//$error="this is an error";
//header('Location: ' . $_SERVER['HTTP_REFERER']);
//exit;
*/

//session_start();
session_start();

$formError=false;

$email = $_POST['email'];
$password = $_POST['pwd'];




if(!(preg_match('/[a-zA-z][a-zA-Z0-9+.+_]*@[a-zA-z]*\.(com|pk|org)\z/', $email)))
{
	$_SESSION['emailError']="Please enter a valid email address i.e. a@b.com or a@b.pk";
	$formError=true;
}
else
{
	$_SESSION['email']=$email;
}

if(strlen($password)==0)
{
	$_SESSION['passwordError']="Please enter your Password";
	$formError=true;
}
else
{
	$_SESSION['password']=$password;
}

if($formError==true)
{	
	header('Location: logIn.php');
}
else
{
	
	
	$result =$mysqli->query("SELECT email,type,id FROM user WHERE email='$email' AND password='$password'");
	$row = $result->fetch_row();
	if($row[0]==$email)
	{
		//$mysqli->query("UPDATE user SET status = '1' WHERE id = '$row[2]'");
		
		if(isset($_POST['remember']) && !empty($_POST['remember']))
		{
			
			$info[0] = $email;
			$info[1] = $password;
			setcookie('rememberMe', serialize($info));
		}
		else
		{
			if(isset($_COOKIE['rememberMe']))
			{
				$check =  unserialize($_COOKIE['rememberMe']);
				
				if($check[0] == $email && $check[1] = $password)
				{
					unset($_COOKIE['rememberMe']);
    				setcookie('rememberMe', null, -1); // the third parameter is of time which is set to -1 so cookie expire automatically
				}
			}	
  
		}



		///////////////////////////////////////////////////////

		$_SESSION['currentUser'] = $row[2];
		
		if($row[1]=="employer")
		{
			header('Location: employerDashboard1.php');			
		}
		else
		{
			header('Location: employeeDashboard1.php');	
		}
	}
	else
	{
		$_SESSION['loginError']="Either email or password is incorrect";
		header('Location: logIn.php');		
	}

	unset($_SESSION['emailError']);
	unset($_SESSION['passwordError']);
	unset($_SESSION['email']);
	unset($_SESSION['password']);
}
