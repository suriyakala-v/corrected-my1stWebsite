<?php
// Start the session
session_start();
include 'DBconn.php';

//1st time login
if(!empty($_POST['username']) && !empty($_POST['password']) ){
    $usr = $_POST['username'];
    $pwd = $_POST['password'];
$queryrun = $conn->prepare("SELECT * FROM user_details where username='$usr' and password='$pwd' ");
$queryrun->execute();//returns 1 or 0
$result = $queryrun->setFetchMode(PDO::FETCH_ASSOC);
$result = $queryrun->fetch();
if(!empty($result)){
    $_SESSION['is_login']=true;
    $_SESSION['userName']=$usr;
    echo json_encode("Valid");
}
else{
    echo json_encode("INVALID! user and password not match");
}
}
//already logged in
elseif(!empty($_SESSION['is_login'])){
    echo json_encode("already loggedin");
}

?>