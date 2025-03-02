<?php

include "connection/connection.php";
include "utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    session_start();

    if (!isset($_SESSION["admin_id"])) {
        return_failure("unauthorized access");
    }

    $sql = "UPDATE users SET verification_status = 'approved' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return_success();
}
