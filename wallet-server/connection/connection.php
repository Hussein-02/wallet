<?php

$server_name="localhost";
$username="root";
$password="";
$db_name="my_project1";

$conn = new mysqli($server_name,$username,$password,$db_name);

if($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}


?>