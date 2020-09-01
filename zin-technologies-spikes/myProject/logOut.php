
<?php
include ('db.php');
session_start();

if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser']))
{
	unset($_SESSION['currentUser']);
}
session_destroy();
header('Location: logIn.php');

