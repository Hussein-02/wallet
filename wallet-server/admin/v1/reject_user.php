<?php

include "../../connection/connection.php";
include_once "../../utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $user_id = $data['user_id'];
    session_start();

    if (!isset($_SESSION["admin_id"])) {
        return_failure("unauthorized access");
    }

    $sql = "UPDATE users SET verification_status = 'rejected' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    return_success();
}
