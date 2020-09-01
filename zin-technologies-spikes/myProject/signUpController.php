<?php

include ('unRegisteredAjaxProtection.php');


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) 
{
	$formError=false;
	$type=$_POST['person'];
	$name=$_POST['name'];
	$email=$_POST['email'];
	$password=$_POST['pwd'];
	$confirmPassword=$_POST['pwd1'];
	$isPassword=false;
	$companyName=$_POST['comp'];


	$nameError="";
	$emailError="";
	$passwordError="";
	$confirmPasswordError="";
	$companyNameError="";


	if(!(preg_match('/^([A-Z][a-z]*)$|^([A-Z][a-z]*\ [A-Z][a-z]*)$/', $name)))
	{
	//echo json_encode("bol"=>1);
		$nameError="Name should only contain Alphabets i.e. Bilal or Bilal Afzal";
		$formError=true;
	}

	if(!(preg_match('/[a-zA-z][a-zA-Z0-9+.+_]*@[a-zA-z]*\.(com|pk|org)\z/', $email)))
	{
		$emailError="Please enter a valid email address i.e. a@b.com or a@b.pk";
		$formError=true;
	}

	if(strlen($password)<8)
	{
		$passwordError="Password should have length >= 8";
		$formError=true;
	}
	else
	{
		$isPassword=true;
	}


	if(!($isPassword==true && $confirmPassword==$password))
	{
		$confirmPasswordError="Please confirm your Password";
		$formError=true;
	}

	if($type=="employer")
	{
		if(strlen($companyName)==0)
		{
			$companyNameError="please enter your company name";
			$formError=true;
		}
	}
	if($formError==true)
	{	

		echo json_encode(array("tip"=>1,"nameError"=>$nameError,"emailError"=>$emailError,"passwordError"=>$passwordError,"confirmPasswordError"=>$confirmPasswordError,"companyNameError"=>$companyNameError));
	}
	else
	{


		$result =$mysqli->query("SELECT email FROM user WHERE email='$email'");

		$row = $result->fetch_row();
		if($row[0]==$email)
		{

			$emailError="Email has already been taken";
			echo json_encode(array("tip"=>1,"nameError"=>$nameError,"emailError"=>$emailError,"passwordError"=>$passwordError,"confirmPasswordError"=>$confirmPasswordError,"companyNameError"=>$companyNameError));		
		}
		else
		{

			$mysqli->query("INSERT INTO user (name, email, Password,type) VALUES ('$name', '$email', '$password','$type')");
			$result =$mysqli->query("SELECT id FROM user WHERE email='$email'");			
			$row = $result->fetch_row();
			if($type=="employer")
			{
				$mysqli->query("INSERT INTO company (userCompanyId,companyName) VALUES ('$row[0]', '$companyName')");			
			}

			$_SESSION['currentUser']=$row[0];
			echo json_encode(array("tip"=>0));	
		}

	}
}
// /\+[0-9]\([0-9][0-9][0-9]\)[0-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/g  for +1(111)111-1111
// /[a-zA-z][a-zA-Z0-9+.+_]*@[a-zA-z]*\.(com|pk|org)/g                 for email


