<?php
echo "hello project 2";

try {

    //below is the connection of php docker container with mysql(not a docker container)
    $conn = new PDO('mysql:host=swedish-team.clipqps3mudb.eu-north-1.rds.amazonaws.com;port=3306;dbname=iapps_demo', 'iapps_demo_user', 'Temp1234$'); //connection with mysql database outside of docker
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";

    $sth = $conn->query("select * from users");
    $result = $sth->fetchAll();

    print_r($result);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}