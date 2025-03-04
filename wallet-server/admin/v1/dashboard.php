<?php

include "../../connection/connection.php";
include_once "../../utils.php";

session_start();

if (!isset($_SESSION["admin_id"])) {
    return_failure("unauthorized access");
}

//to store dashboard info in
$dashboard = [];

//total users
$sql1 = "SELECT COOUNT(*) as total_users FROM users";
$result = $conn->query($sql1);
$dashboard["total_users"] = $result->fetch_assoc()["total_users"];

//total transactions
$sql2 = "SELECT COOUNT(*) as total_transactions FROM transactions";
$result = $conn->query($sql1);
$dashboard["total_transactions"] = $result->fetch_assoc()["total_transactions"];

echo json_encode(["success" => true, "data" => $dashboard]);
$conn->close();
