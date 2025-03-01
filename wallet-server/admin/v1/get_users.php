<?php

include "connection/connection.php";
include "utils.php";

session_start();

if (!isset($_SESSION["admin_id"])) {
    return_failure("unauthorized access");
}

$sql = "SELECT user_id, email,phone,username, role,verification_status FROM users";
$result = $conn->query($sql);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(["success" => true, "users" => $users]);
$conn->close();
