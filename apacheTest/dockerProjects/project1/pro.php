<?php
echo "hello i am bilal project1" . '</br>';

try {


//$conn = new PDO('mysql:host=quizzical_khayyam;port=3306;dbname=mydb', 'user', 'pw');
$conn = new PDO('mysql:host=mysql-container;port=3306;dbname=mydb', 'user', 'pw');
//$mysqli = new mysqli("localhost","user","pw","mydb", 3306);


//$conn = new PDO('mysql:host=localhost;dbname=iapps_master_db', 'root', 'password');

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 echo "Connected successfully"; 


$sth = $conn->query("select * from users");
$result = $sth->fetchAll();

print_r($result);
}

catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
