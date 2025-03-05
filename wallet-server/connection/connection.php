<?php

$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "my_project1";

$conn = new mysqli($server_name, $username, $password, $db_name);

if ($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}

//uncomment if cors problem
// header('Access-Control-Allow-Origin:http://127.0.0.1:5500');
// header('Access-Control-Allow-Methods:POST,GET,OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type,Authorization');