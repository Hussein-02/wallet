<?php

include "connection/connection.php";
include "utils.php";

session_start();

if (!isset($_SESSION["admin_id"])) {
    return_failure("unauthorized access");
}

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    return_success();
} else {
    return_failure("failed to delete user");
}

$stmt->close();
$conn->close();
