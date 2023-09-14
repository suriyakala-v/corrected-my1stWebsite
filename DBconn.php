<?php
//db info
$servername = "192.168.11.49";
$productname = "root";
$password = "vins35@123456";
//db connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=surya", $productname,$password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//settting attributes
    //echo "Connected successfully";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>