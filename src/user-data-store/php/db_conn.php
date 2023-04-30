<?php

$dsn = "mysql:host=localhost;dbname=userdatastore;port=";
$user = "";
$pswd = "";

try{
    $conn = new PDO($dsn, $user, $pswd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "connection successful";
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}