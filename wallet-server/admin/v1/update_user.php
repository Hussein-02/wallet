<?php

include "connection/connection.php";
include "utils.php";

if (!isset($_SESSION["admin_id"])) {
    return_failure("unauthorized access");
}

$id = $_GET['id'];
$data = json_decode(file_get_contents("php://input"), true);

//fields to update
$email = $data["email"];
$phone = $data["phone"];
$username = $data["username"];
$role = $data["role"];

$sql = "UPDATE users SET email = ?, phone = ?, username = ?, role = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $email, $phone, $username, $role, $id);

if ($stmt->execute()) {
    return_success();
} else {
    return_failure("failed to update user");
}

$stmt->close();
$conn->close();
