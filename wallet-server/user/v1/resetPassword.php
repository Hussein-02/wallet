<?php

include "../../connection/connection.php";
include_once "../../utils.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $email = $data['email'];
    $password = $data['password'];
    $confirmPassword = $data['confirmPassword'];

    if ($password === $confirmPassword) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);

        if ($stmt->execute()) {
            return_success();
        } else {
            return_failure("Error updating password");
        }

        $stmt->close();
    } else {
        return_failure("passwords do not match");
    }
    $conn->close();
}
